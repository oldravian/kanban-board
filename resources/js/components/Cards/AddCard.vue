<template>
    <div>
        <Modal name="my-first-modal" :shiftY="0.3" :clickToClose="false" height="350">
            <form @submit.prevent="addCard" class="p-4">
            <h4>Create New Card</h4>

                    <div class="mb-1">
                        <label class="form-label">Card Title</label>
                        <input
                            name="title"
                            required
                            type="text"
                            class="form-control"
                            v-model="card.title"
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Card Description</label>
                            <textarea name="description"
                            required
                            class="form-control"
                            v-model="card.description" rows="4">
                            </textarea>
                    </div>
                    <div align="right">
                        <button class="btn btn-danger btn-sm" type="button" @click="hideModal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Add
                        </button>
                    </div>
            </form>
        </Modal>
    </div>
</template>

<script>

export default {
    name: "AddCard",
    props: ['column_id', 'weight'],
    data() {
        return {
            card: {
              title: null,
              description:null
            },
        };
    },
    watch: { 
    column_id: function(newVal, oldVal) { // watch it
      if(newVal!=null){
        this.showModal();
      }
      }
    },
    methods: {
        addCard () {
            let self = this;

            this.card.weight = this.weight;
            this.card.column_id = this.column_id;

            this.axios
              .post("/cards", this.card)
              .then((response) => {
                  let data = response.data;
                  self.$emit('cardAdded', data.result.item)
                  self.hideModal();

                  //reset fields
                  self.card.title=null;
                  self.card.description=null;
              })
              .catch((errors) => {
                  let response = errors.response;
                  alert(response.data.message);
              })
              .finally(function () {});
        },
        showModal(){
            this.$modal.show('my-first-modal');
        },
        hideModal () {
            this.$modal.hide('my-first-modal');
            this.$emit('resetData')
        }
    },
    
    
};
</script>
