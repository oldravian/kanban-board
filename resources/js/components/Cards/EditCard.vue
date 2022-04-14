<template>
    <div>
        <Modal name="edit-modal" :shiftY="0.3" :clickToClose="false" height="350">
            <form @submit.prevent="editCard" class="p-4">
            <h4>Edit Card</h4>

                    <div class="mb-1">
                        <label class="form-label">Card Title</label>
                        <input
                            name="title"
                            required
                            type="text"
                            class="form-control"
                            v-model="edit_card.title"
                            />
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Card Description</label>
                            <textarea name="description"
                            required
                            class="form-control"
                            v-model="edit_card.description" rows="4">
                            </textarea>
                    </div>
                    <div align="right">
                        <button class="btn btn-danger btn-sm" type="button" @click="hideModal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            Update
                        </button>
                    </div>
            </form>
        </Modal>
    </div>
</template>

<script>

export default {
    name: "EditCard",
    props: ['card'],
    data() {
        return {
        };
    },
    computed: {
        edit_card: function () {
            return this.card
        }
    },
    watch: { 
    card: function(newVal, oldVal) { // watch it
      if(newVal!=null){
        this.showModal();
      }
      }
    },
    methods: {
        editCard () {
            let self = this;
            this.axios
              .put(`/cards/${this.edit_card.id}`, this.edit_card)
              .then((response) => {
                  let data = response.data;
                  self.$emit('cardUpdated', data.result.item)
                  self.hideModal();
              })
              .catch((errors) => {
                  let response = errors.response;
                  alert(response.data.message);
              })
              .finally(function () {});
        },
        showModal(){
            this.$modal.show('edit-modal');
        },
        hideModal () {
            this.$modal.hide('edit-modal');
            this.$emit('resetData')
        }
    },
    
    
};
</script>
