<template>
	<div class="login-modal-container animated fadeIn" @click="outsideClick($event)">
		<transition
			name="custom-classes-transition"
		    enter-active-class="animated fadeInDown"
		    leave-active-class="animated fadeOutUp">
			<span v-if="backendError" class="backend-error">{{backendError}}</span>		
		</transition>
		<div class="login-modal-content animated fadeInUp">
			<div class="modal-indicator"><span>Login</span></div>
			<div class="form-element">
				<label for="email">Email:</label>
				<input name="email" v-validate="'required|email'" type="email" class="cool-input" v-model="email" @keyup.enter='validate' autofocus>
				<span class="error-text animated fadeInDown" v-show="errors.has('email')">{{ errors.first('email') }}</span>
			</div>
			<div class="form-element">
				<label for="password">Password:</label>
				<input name="password" v-validate="'required'"  type="password" class="cool-input" v-model="password" @keyup.enter='validate'>
				<span class="error-text animated fadeInDown" v-show="errors.has('password')">{{ errors.first('password') }}</span>
			</div>
			<div class="form-element">
				<button class="cool-btn" @click="validate">
					<span v-show="!loading">login</span>
					<img v-show="loading" src="/img/preloader.svg" alt="">
				</button>
			</div>
			<div class="additional">
				<a href="">Forgot password?</a>
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

.error-text{
	font-size: 13px;
    position: absolute;
    color: #f57e7d;
    bottom: -10px;
    right: 30px;
}

.additional{
    padding-bottom: 10px;
    height: 25px;
    font-size: 15px;
    text-align: right;

    a{
	    color: #0097e6;
    }
}


.backend-error{
	background: #ff4757a1;
    color: #fff;
    position: absolute;
    top: 0;
    padding: 10px;
    text-align: center;
    width: 100%;
}


.form-element{
	.cool-btn{
	    display: flex;
	    justify-content: center;
    	align-items: center;

    	img{
    		width:25px;
    	}
	}
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
				password:'',
				backendError:'',
				loading:false
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
				this.loading=true;
				var Ref= this;
				axios.post('/api/user/login',
				{email: this.email,	password: this.password},
				{headers: {'X-Requested-With':'XMLHttpRequest'}})
				.then((response)=>{
					
					const status = JSON.parse(response.status);

				    if (status == '200'){
				    	localStorage.setItem('token', response.data.token);
				    	Ref.loading=false;
				    	Ref.$router.push({name:'chat'});
				    }
				})
				.catch((error)=>{
					// console.log(error.response);
			    	Ref.loading=false;	
					if (error.response.status==401 || error.response.status==422 ) this.backendError="Incorrect Email or password, please try again."
					else this.backendError=error.response.data.message;
					this.resetError();
				});
			},

			validate(){
				this.$validator.validate().then(result => {
					if (result) {
						this.authenticate();
					}
				});
			},

			resetError(){
				setTimeout(()=>{
					this.backendError='';
				},4000);
			}
		},
		mounted(){
  			window.onkeyup= event =>{
  				if (event.keyCode==27) this.closeLogin();
  			}
  		},

	}
</script>