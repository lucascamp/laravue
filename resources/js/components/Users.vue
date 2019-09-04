<template>
    <div class='row'>

        <b-modal id="modal-1" size="lg" hide-header hide-footer>
            <h2 v-if="!isHidden" class="mb-3">Cadastrar usuário</h2>
            <h2 v-if="isHidden" class="mb-3">Editar {{ user.name }}</h2>
            <form @submit.prevent="addUser" class='mb-3'>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Nome" v-model="user.name">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Email" v-model="user.email">
                </div>

                <button @click="$bvModal.hide('modal-1')" v-on:click="isHidden = false" v-if="!isHidden" class="btn btn-success btn-block">Salvar</button>

                <button @click="$bvModal.hide('modal-1')" v-on:click="isHidden = false"  v-if="isHidden" class="btn btn-primary btn-block">Editar</button>
            
            </form>
        </b-modal>
  
        <div class="offset-md-1 mt-5 col-md-10">
            <b-button class="btn btn-success" v-b-modal.modal-1>Registrar</b-button>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-end">
                    
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="#" 
                    @click="fetchUsers(pagination.prev_page_url)" tabindex="-1">Anterior</a>
                    </li>

                    <li class="page-item disabled">
                        <a class="page-link text-dark" href="#" >Página {{pagination.current_page}} de  {{pagination.last_item}} </a>
                    </li>
                    
                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a class="page-link" href="#" @click="fetchUsers(pagination.next_page_url)">Próximo</a>
                    </li>
                </ul>
            </nav>

            <div class="card card-body mb-2" v-for="user in users" v-bind:key="user_id">
                <div class='row'>
                    <div class="col-md-9">
                        <h3>{{ user.id }} - {{ user.name }}</h3>
                        <p>{{ user.email }}</p>
                    </div>
                    <div class="col-md-3">
                        <b-button button @click='editUser(user)' v-on:click="isHidden = true" class="btn btn-secondary" v-b-modal.modal-1>Editar</b-button>
                        <button @click='deleteUser(user.id)' class="btn btn-danger"> Remover </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>

export default {
    data(){
        return{
            users:[],
            user:{
                id:'',
                name:'',
                email:''
            },
            user_id : '',
            pagination: {},
            edit:false,
            isHidden: false
        }
    },

    created(){
        this.fetchUsers();
    },

    methods:{
        fetchUsers(page_url){
            let vm = this;
            page_url = page_url || 'api/users'
            fetch(page_url)
            .then(res => res.json())
            .then(res => {
                console.log(res.data);
                this.users = res.data;
                vm.makePagination(res.last_page_url, res.next_page_url, res.prev_page_url, res.current_page, res.last_page);
            })
            .catch(err => console.log(err));
        },

        makePagination(last_page_url, next_page_url, prev_page_url, current_page, last_page)
        {
            let pagination = {
                current_page: current_page,
                last_page: last_page_url,
                next_page_url: next_page_url,
                prev_page_url: prev_page_url,
                last_item: last_page,
            }

            this.pagination = pagination;
        },

        deleteUser(id)
        {
            swal({
                title: 'Deseja deletar o registro?',
                text: "Essa ação não pode ser revertida",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, remover'
                }).then((result) => {
                    fetch('api/users/delete/'+id, { method: 'DELETE' })
                        .then(res => res.json())
                        .then(res => {
                            swal("Usuário removido", "", "success");
                            this.fetchUsers();
                        })
                    .catch(err => console.log(err));
            })
        },

        addUser()
        {
            if(this.edit===false){
                fetch('api/users/store', { 
                    method: 'post',
                    body: JSON.stringify(this.user),
                    headers: {'content-type': 'application/json'}
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.user.name ='';
                    this.user.email ='';
                    swal("Sucesso", "Usuário adicionado", "success");
                    this.fetchUsers();
                })
                .catch(err => console.log(err));
            }
            else
            {
                fetch('api/users/update/'+this.user.id , { 
                    method: 'PUT',
                    body: JSON.stringify(this.user),
                    headers: {'content-type': 'application/json'}
                })
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.user.name ='';
                    this.user.email ='';
                    swal("Sucesso", "Usuário atualizado", "success");
                    this.fetchUsers();
                })
                .catch(err => console.log(err));
            }
        },

        editUser(user){
            this.edit = true;
            this.user.id = user.id;
            this.user.user_id = user.id;
            this.user.name = user.name;
            this.user.email = user.email;
            toggleEditText('Editar');
        },

        toggleEditText: function(btnText) {
            app.button.text = btnText;
        }
    }
}
</script>