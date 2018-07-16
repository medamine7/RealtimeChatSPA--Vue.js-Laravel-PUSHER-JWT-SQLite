<template>
	<div class="container">
		<div class="art"></div>
		<login-modal v-if="loginModal"></login-modal>
		<register-modal v-if="registerModal"></register-modal>
		<div class="header">
			<div class="logo-container">
				<img src="/img/logo.png" alt="">
			</div>
			<div class="nav">
				<ul>
					<li><a href="" class="active">Home</a></li>
					<li><a href="">Features</a></li>
					<li><a href="">About</a></li>
				</ul>
			</div>
		</div>
		<div class="hero">
			<h1>Welcome to <img src="/img/logo.png" alt=""></h1>
			<h5><span>J</span>ust another chatting plateform, but also a reference work that belongs to Mohamed Amine Baladi :)</h5>
			<div class="btn-container">
				<button class="cool-btn" @click="openLogin">Login</button>
				<button class="cool-btn" @click="openRegister">Register</button>
			</div>
		</div>
	</div>
</template>

<script>
	import loginModal from '../loginModal'
	import registerModal from '../registerModal'
	import {mapState, mapActions} from 'vuex'
	export default{
		name:'home',
		computed: {
			...mapState([
		    	'loginModal',
		    	'registerModal'
		    ]),
		},
		components: {
			"login-modal" : loginModal,
			"register-modal" : registerModal,
		},
		methods: {
			...mapActions([
				'openLogin',
				'openRegister',
				'closeLogin',
				'closeRegister'
			])
		}
		
	}
</script>


<style lang="scss">


.logo-container{

	img{
		width:130px;
	}
}


.header{
	width:100%;
	display:flex;
	justify-content: space-between;
	margin-bottom: 150px;
}

.nav{
	width:400px;
	font-size:15px;
	position: relative;
	z-index:1;
	text-transform: uppercase;
	font-family: 'karla', sans-serif;
	
	a{
		text-decoration:none;
		color:#767b85;
	}
	ul{
		display:flex;
		justify-content: space-between;
	}

	li{
		list-style:none;
		font-weight:bold;
		font-size:15px;
		letter-spacing: 1px;
	}
}

.hero{
	color: #0c1326;
	font-family: 'montserrat', sans-serif;
	position: relative;
	z-index:1;

	h1{
		font-size:50px;
	    display: flex;
	    align-items: center;

		img{
			width: 230px;
    		margin: 0 10px;
		}
	}

	h5{
		color: #6d727d;
	    font-size: 17px;
	    font-family: 'karla';
	    width: 530px;
	    line-height: 40px;	

	    span{
	    	font-size: 20px;
	    }
	}


	.btn-container{
	    width: 330px;
	    display: flex;
    	justify-content: space-between;

	}


	button{		
		&:nth-of-type(2){
			background:#0097e6;
		}
	}
}

.art{
	background:#fff ;
    width: 700px;
    height: 700px;
    top: -300px;
    border-radius: 50%;
    position: absolute;
    right: -229px;
}

.active{
	color: #0c1326 !important;
}


</style>