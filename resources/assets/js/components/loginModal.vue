<template>
	<div class="login-modal-container animated fadeIn" @click="outsideClick($event)">
		<div class="login-modal-content animated fadeInUp">
			<div class="modal-indicator"><span>Login</span></div>
			<div class="form-element">
				<label for="email">Email:</label>
				<input type="text" class="cool-input" v-model="email" @keyup.enter='validate' autofocus>
			</div>
			<div class="form-element">
				<label for="password">Password:</label>
				<input type="password" class="cool-input" v-model="password" @keyup.enter='validate'>
			</div>
			<div class="form-element">
				<button class="cool-btn" @click="validate">login</button>
			</div>
		</div>
	</div>
</template>


<style lang="scss">
	.login-modal-container{
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


	.login-modal-content{
		width:400px;
		margin:auto;
		box-shadow: 0 10px 43px 5px rgba(0, 0, 0, 0.2);
	    border-radius: 20px;
		background:#fff;
		background:#f5f6fa;
		justify-content:space-around;
		position:relative;
		display:flex;
		flex-direction:column;
		align-items:center;
}

</style>


<script>
	import {mapActions} from 'vuex'
	import axios from 'axios'
	export default {
		name: 'loginModal',
		data(){
			return {
				email:'',
				password:''
			}
		},
		methods: {
			...mapActions([
				'closeLogin'
			]),
			outsideClick(e){
				if(e.target==document.querySelector('.login-modal-container')) this.closeLogin();
			},

			authenticate(){
				var Ref= this;
				axios.post('/api/user/login',
				{email: this.email,	password: this.password},
				{headers: {'X-Requested-With':'XMLHttpRequest'}})
				.then((response)=>{
					
					const status = JSON.parse(response.status);

				    if (status == '200'){
				    	localStorage.setItem('token', response.data.token);
				    	Ref.$router.push({name:'chat'});
				    }
				})
				.catch((error)=>{
					console.log(error);
				});
			},

			validate(){
				this.authenticate();
			}
		},
		mounted(){
  			window.onkeyup= event =>{
  				if (event.keyCode==27) this.closeLogin();
  			}
  		},
	}
</script>