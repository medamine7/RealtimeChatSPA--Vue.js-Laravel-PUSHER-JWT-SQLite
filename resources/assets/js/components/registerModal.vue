<template>
	<div class="register-modal-container animated fadeIn" @click="outsideClick($event)">
		<div class="register-modal-content animated fadeInUp">
			<div class="modal-indicator"><span>Register</span></div>
			<div class="form-element">
				<label for="name">Name:</label>
				<input class="cool-input" type="text" v-model="name">
			</div>
			<div class="form-element">
				<label for="email">Email:</label>
				<input class="cool-input" type="email" v-model="email">
			</div>
			<div class="form-element">
				<label for="password">Password:</label>
				<input class="cool-input" type="password" v-model="password">
			</div>
			<div class="form-element">
				<label for="password">Repeat password:</label>
				<input class="cool-input" type="password" v-model="password_confirmation"> 
			</div>
			<div class="form-element">
				<button class="cool-btn" @click="registerUser">Register</button>
			</div>
		</div>
	</div>
</template>


<style lang="scss">
	.register-modal-container{
		position:fixed;
		top:0;
		left:0;
		right:0;
		bottom:0;
		background: rgba(0,0,0,.5);
		display:flex;
		align-items:center;
		z-index:8;
	}


	.register-modal-content{
		width:400px;
		margin:auto;
		box-shadow: 0 10px 43px 5px rgba(0, 0, 0, 0.2);
	    border-radius: 20px;
		background:#fff;
		background:#f5f6fa;
		display:flex;
		justify-content:space-around;
		position:relative;
		flex-direction:column;
		align-items:center;
	}
</style>

<script>
	import {mapActions} from 'vuex'
	import axios from 'axios'
  	axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
	export default {
		name: 'registerModal',
		data(){
			return {
				name:'',
				email:'',
				password:'',
				password_confirmation:''
			}
		},
		methods: {
			...mapActions([
				'closeRegister'
			]),
			outsideClick(e){
				if(e.target==document.querySelector('.register-modal-container')) this.closeRegister();
			},
			registerUser(){
				axios.post('/api/user/register', {
		            name: this.name,
		            email: this.email,
		            password: this.password,
		            password_confirmation: this.password_confirmation
		        })
	          .then(function (response) {
	            console.log(response);
	          })
	          .catch(function (error) {
	            console.log(error);
	          });
			}

		},
		mounted(){
  			window.onkeyup= event =>{
  				if (event.keyCode==27) this.closeRegister();
  			}
  		},
	}
</script>