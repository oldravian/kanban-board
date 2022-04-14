<template>
    <div class="container-fluid mt-2" style="overflow-x: auto; white-space: nowrap;">
        <div style="min-height: 700px;">
            <div style="display: inline-block; float: none;" class="col-md-3 mr-2" v-for="(column, index) in columns" :key="column.title">
                <draggable
                    v-model="column.cards"
                    class="list-group"
                    draggable=".item"
                    group="a"
                    @end="onEnd($event)"
                    :data-index="index"
                    
                >
                    <div
                        class="list-group-item item" style="cursor:pointer"
                        v-for="(card, inner_index) in column.cards"
                        :key="card.title"
                        @click="editCard(index, inner_index)"
                    >
                        {{ card.title }}
                    </div>

                    <div slot="header" class="btn-group list-group-item">
                        <strong>{{ column.title }}</strong>
                        <button
                            style="float: right"
                            class="btn btn-danger btn-sm"
                            @click="removeColumn(column.id, index)"
                        >
                            X
                        </button>
                    </div>
                    <div
                        slot="footer"
                        class="btn-group list-group-item"
                        role="group"
                        aria-label="Basic example"
                    >
                        <button class="btn btn-primary btn-sm" @click="addCard(index)">
                            Add new card
                        </button>
                    </div>
                </draggable>
            </div>

            <div class="col-md-3 mr-2" style="display: inline-block; float: none;">
                <form v-if="column_add_state" @submit.prevent="addColumn">
                    <div class="mb-1">
                        <label class="form-label">Column Title</label>
                        <input
                            name="title"
                            required
                            type="text"
                            class="form-control"
                            v-model="column.title"
                            />
                    </div>

                    <div align="right">
                      <button class="btn btn-danger btn-sm" @click="column_add_state=false">
                          Cancel
                      </button>
                      <button type="submit" class="btn btn-primary btn-sm">
                          Add
                      </button>
                    </div>
                </form>
                <button v-else class="btn btn-primary btn-sm" @click="column_add_state=true">
                    Add new column
                </button>
            </div>

            <a style="position: fixed; bottom: 10%; right: 10%;" target="_blank" role="button" class="btn-success btn-sm" href="/export-db">Export DB</a>
        </div>

        <AddCard 
        :column_id="column_id_for_add_card"
        :weight="weight_for_add_card"
        @resetData="resetAddCard"
        @cardAdded="cardAdded"
        ></AddCard>

        <EditCard 
        :card="card_under_edit"
        @resetData="resetEditCard"
        @cardUpdated="cardUpdated"
        ></EditCard>
        
    </div>
</template>

<script>
import draggable from "vuedraggable";
import AddCard from './Cards/AddCard';
import EditCard from './Cards/EditCard';

export default {
    name: "KanbanBoard",
    components: {
        draggable, AddCard, EditCard
    },
    data() {
        return {
            column_add_state : false,
            columns: [],
            column: {
              title: null
            },

            column_id_for_add_card:null,
            column_index_for_add_card:null,
            weight_for_add_card:0,
            
            card_under_edit:{},
            column_index_under_edit:null,
            card_index_under_edit:null,
        };
    },
    created() {
        let self = this;
        this.axios
            .get("/columns")
            .then((response) => {
                let data = response.data;
                self.columns = data.result.items;
            })
            .catch((errors) => {
                let response = errors.response;
            })
            .finally(function () {});
    },
    methods: {
        addCard: function (index) {
          this.column_id_for_add_card = this.columns[index].id;
          this.column_index_for_add_card = index;
          let cards_length = this.columns[index].cards.length;
          
          if(cards_length==0){
            this.weight_for_add_card=0.1;
          }
          else{
            //card weight will be +1 w.r.t last card weight
            this.weight_for_add_card = Number(this.columns[index].cards[cards_length-1].weight) + 0.1;
          }
        },
        cardAdded(card){
          this.columns[this.column_index_for_add_card].cards.push(card);
        },
        editCard(column_index, card_index){
          this.column_index_under_edit = column_index,
          this.card_index_under_edit = card_index,
          this.card_under_edit = this.columns[column_index].cards[card_index];
        },
        cardUpdated(card){
          this.columns[this.column_index_under_edit].cards[this.card_index_under_edit] = card;
        },
        addColumn: function () { 
          let self = this;
          this.axios
              .post("/columns", this.column)
              .then((response) => {
                  let data = response.data;
                  self.columns.push(data.result.item);
                  self.column_add_state = false;

                  //reset field
                  self.column.title=null;
              })
              .catch((errors) => {
                  let response = errors.response;
                  alert(response.data.message);
              })
              .finally(function () {});
        },
        removeColumn: function (id, index) {
          if(!confirm("Are you sure?")) return; 
          let self = this;
          this.axios
              .delete("/columns/"+id)
              .then((response) => {
                  let data = response.data;
                  self.columns.splice(index, 1);
              })
              .catch((errors) => {
                  let response = errors.response;
                  alert(response.data.message);
              })
              .finally(function () {});
        },
        resetAddCard: function(){
          this.column_id_for_add_card=null;
          this.column_index_for_add_card=null;
          this.weight_for_add_card=0;
        },
        resetEditCard: function(){
          this.card_under_edit={};
        },

        onEnd(e){
          let newIndex = e.newDraggableIndex;
          let column_index = e.to.getAttribute('data-index');

          let current_column = this.columns[column_index];
          let current_card = current_column.cards[newIndex];



          if(newIndex==0 && current_column.cards.length==1){ //there is just one card in the column
           // alert(1);
              current_card.column_id = current_column.id;
            }
            else if(newIndex==0 && current_column.cards.length > 1){ //card is at first index and there is another card at newIndex+1
            //alert(2);
              
              current_card.column_id = current_column.id;
              current_card.weight = Number(current_column.cards[newIndex+1].weight) - 0.1; //current card has less weight than next card
            }
            else if(newIndex==current_column.cards.length-1 && current_column.cards.length > 1){ //card is at last index and there is another card at newIndex-1
            //alert(3);
              
              current_card.column_id = current_column.id;
              current_card.weight = Number(current_column.cards[newIndex-1].weight) + 0.1; //current card has more weight than previous card

            }
            else{ //card is between two other cards
            //alert(4);
              
              current_card.column_id = current_column.id;
              let next = Number(current_column.cards[newIndex+1].weight);
              let previous = Number(current_column.cards[newIndex-1].weight);

              current_card.weight = (next+previous)/2; //current card has median weight
            }


            //update the card
            let self = this;
            this.axios
              .put(`/cards/${current_card.id}`, current_card)
              .then((response) => {
                  let data = response.data;
              })
              .catch((errors) => {
                  let response = errors.response;
                  alert(response.data.message);
              })
              .finally(function () {});
        }
    },
};
</script>
<style scoped></style>
