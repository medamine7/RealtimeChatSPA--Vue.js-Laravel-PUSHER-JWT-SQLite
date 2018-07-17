<template>
	<div class="chat-container">
		<div class="sidebar">
			<div class="main-menu">
				<div class="avatar-container">
					<div class="avatar">
						<img :src="userAvatar" alt="">
					</div>
				</div>
				<h3>{{userName}}</h3>
				<ul>
					<li><p>Unread Messages<span>3</span></p></li>
					<li @click="settings=true"><p>settings</p></li>
					<li @click="logout"><p>log out</p></li>
				</ul>
			</div>
			 <transition
			    name="custom-classes-transition"
			    enter-active-class="animated fadeInLeft"
			    leave-active-class="animated fadeOutLeft"
			  >
				<div class="settings" v-if="settings">
					<h1 @click="settings=false" class="arrow-back"><i class="fas fa-arrow-left"></i></h1>
					<h5>Change avatar</h5>
					<input type="file" id="avatar-input" @change="changeAvatarInit">
					<div class="avatar-container" @click="browseNewAvatar">
						<span><i class="fas fa-camera"></i></span>
						<div class="avatar">
							<img :src="tempUserAvatar" alt="">
						</div>
					</div>
					<button id="save-avatar" class="cool-btn" disabled @click="submitAvatarChange">Save avatar</button>					
					<h5>Change password</h5>
					<input type="text" class="cool-input" placeholder="Your Password" v-model="password">
					<input type="text" class="cool-input" placeholder="New Password" v-model="newPassword">
					<button id="save-password" class="cool-btn" :disabled="(!password || !newPassword)">Save password</button>					
				</div>
			</transition>
		</div>
		<div class="chat" v-if="chosen">
			<div class="chat-header">
				<h3>{{currentContact.name}}</h3>
			</div>
			<div class="chat-content" v-if="current_conversation">
				<div v-if="!loading" :class="[(message.author_id == userId)? 'own-message' : 'other-message', 'message']" v-for="message in current_conversation.messages" >
					<div class="message-wrapper">
						<div class="chat-avatar">
							<img :src="currentContact.avatar" alt="" v-if="message.author_id != userId">
						</div>
						<p>{{message.body}}</p>
					</div>
				</div>
				<img v-show="loading" id="chat-preloader" src="/img/bpreloader.svg" alt="">
			</div>
			<div class="text-field">
				<textarea placeholder="Type in your message here..." v-model="message" @keydown.enter.prevent="sendMessage" autofocus></textarea>
				<button class="cool-btn" @click="sendMessage">Send</button>
			</div>
		</div>
		<div class="chat-placeholder" v-if="!chosen">
			<img src="/img/paper-plane.svg" alt="">
			<h1>Choose a convertsation</h1>
		</div>
		<div class="conversations">
			<div class="conversations-wrapper" v-if="(!searching && conversations.length!==0)">
				<div :class="['conversation', {'active-conversation' : (activeConversation==conversation.conversation_id)}]" v-for="conversation in conversations" @click="getConversation(conversation.conversation_id,conversation.user);activate(conversation.conversation_id)"  :key="conversation.id">
					<div>
						<img :src="conversation.user.avatar" alt="">
					</div>
					<div>
						<h3>{{conversation.user.name}}</h3>
						<p>{{conversation.body}}</p>
					</div>
				</div>
			</div>
			<div class="conversations-placeholder" v-if="(conversations.length===0 && !searching)">
				<img src="/img/friends.svg" alt="">
				<h3>Hurray! you have arrived, now search for friends and start the adventure.</h3>
			</div>
			<div class="conversations-wrapper search-results" v-if="searching">
				<div v-for="user in result" class="conversation" @click="getFoundConversation(user)">
					<div>
						<img :src="user.avatar" alt="">
					</div>
					<div>
						<h3>{{user.name}}</h3>
					</div>
				</div>
			</div>
			<div class="conversation-search">
				<input class="cool-input" type="text" placeholder="Search for people..." v-model="keyword" @keyup='search'>
			</div>
		</div>
	</div>
</template>


<style lang="scss">
	.chat-container{
		background:#fff;
		height:100vh;	
		display:flex;
		width:100%;
	}

	.sidebar,
	.conversations{
		position: relative;
		height:100%;
		display:flex;
		flex-direction: column;
		background:#f5f6fa;
		width:300px;
		align-items:center;
	}

	.conversations{
		justify-content: space-between;
		h3{
			text-transform:capitalize;
		}
	}

	.conversation-search{
		border-top: 1px solid #e6e8ea;
	    padding: 5px;
    	box-sizing: border-box;
		width: 100%;

		.cool-input{
			outline:none;
		}
	}

	.conversations-wrapper{
		display: flex;
    	flex-direction: column;
    	width: 100%;
	}


	.avatar-container{
		width: 100px;
		height:100px;
		border-radius:50%;
		position: relative;
		
		.avatar{
			align-items: center;
			justify-content: center;
			overflow: hidden;
			border-radius:50%;
			display:flex;
			width:100%;
			height:100%;

		}

		img{
			height: 110%;
			width: auto;
		}
	}


	.chat, 
	.chat-placeholder{
		flex:1;
		display:flex;
		position: relative;
		align-items: center;
		width:auto;
		height:100%;
		padding: 0 10px;
		flex-direction: column;
	}

	.chat-placeholder{
		justify-content:center;
	}

	.chat{
		justify-content: space-between;
	}

	.chat-placeholder{
		color:#57606f;
		img{
			height:auto;
			width:100px;
		}
	}


	.chat-content{
		position: relative;
		height:100%;
		width:100%;
		padding-bottom: 20px;
		overflow-y:auto;
		display: flex;
		flex-direction: column-reverse;

		/* width */
		&::-webkit-scrollbar {
		    width: 10px;
		}

		/* Track */
		&::-webkit-scrollbar-track {		
		    background: #f1f1f1;
		}

		/* Handle */
		&::-webkit-scrollbar-thumb {
		    background: #ced6e0; 
		    border-radius:20px;
		}

		/* Handle on hover */
		&::-webkit-scrollbar-thumb:hover {
		    background: #aeb5bd; 
		}
	}

	.own-message{
		justify-content: flex-end;
		p{
			background:#1e90ff;
		}
		.message-wrapper{
			justify-content: flex-end;
		}
	}

	.other-message{
		p{
			background:#747d8c;
		}
	}

	.message{
		width:100%;
		display:flex;
		align-items:center;
		flex-shrink:0;
		p{
			border-radius: 20px;
			padding: 7px 10px;
		    word-break: break-word;
			color:#fff;
			display: inline-block;
			margin: 5px;
		}

		.message-wrapper{
			width:50%;
			display:flex;
			align-items: center;
		}
	
	}

	.chat-avatar{
		height:30px;
		margin-right:5px;
		width:30px;
		border-radius:50%;
		flex-shrink: 0;
		overflow: hidden;
		display:flex;
		justify-content: center;
		align-items: center;

		img{
			height: 110%;
			width: auto;
		}
	}


	.text-field{
		display: flex;
		width:100%;
		align-items:center;
		min-height: 100px;
		border-top: 1px solid #e6e8ea;

		.cool-btn{
			width:100px !important;
			font-size:15px;
			margin: 0 5px;
		}

		textarea{
			font-family:karla;
			border:none;
			border:1px solid #e6e8ea;
			font-size:16px;
			border-radius: 20px;
			min-height:1em;
			padding:10px;
			outline:none;
			width:auto;
			resize:none;
			flex:1;
		}
	}


	.chat-header{
		border-bottom: 1px solid #e6e8ea;
		display: flex;
		justify-content: center;
		align-items: center;
		flex-shrink:0;
		width:100%;
		height:80px;

		h3{
			text-transform:capitalize;
		}
	}

	.sidebar{
		justify-content: center;

		.main-menu{
			display:flex;
			width:100%;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			flex:1;
		}

		#avatar-input{
			opacity:0;
			position:absolute;
			left:-500px;
		}

		.settings{
			position:absolute;
			justify-content: space-around;
			top:0;
			left:0;
			z-index:3;
			background:#f5f6fa;
			align-items: center;
			right:0;
			bottom:0;
			padding:10px 20px;
			display: flex;
			flex-direction: column;

			.cool-btn{
				width: 125px;
			    height: 30px;
			    font-size: 12px;
				&:disabled{
					box-shadow:none;
					background:#c3c3c3;
					cursor: default;
					transition: all .2s;
				}
			}
			.avatar-container{
				cursor:pointer;
				overflow:hidden;

				span{
					position: absolute;
				    top: 0;
				    left: 0;
				    justify-content: center;
				    display: flex;
				    right: 0;
				    bottom: 0;
				    background: #000;
				    color: #fff;
				    opacity: 0;
				    transition: all .2s;
				    font-size: 40px;
				    align-items: center;
				    z-index: 4;
				}

				&:hover span{
					opacity:0.8;
				}
				
			}


			h1,h5{
				margin:0;
			}

			h5{
				border-top: 1px solid #d0d0d0;
			    padding-top: 20px;
			    width: 100%;
			    text-align: center;
			}

			.arrow-back{
				cursor:pointer;
				color:#70a1ff;
			}

			h5{
			    font-size: 20px;
				text-transform: uppercase;
				color:#57606f;
			}
		}

		h3{
			text-transform: capitalize;
		}

		ul{
			display:flex;
			justify-content: center;
			align-items: center;
			width:100%;
			color:#57606f;
			flex-direction: column;
			padding:0;
		}
		
		li{
			justify-content: center;
		    padding: 20px 0;
		    cursor:pointer;
		    display: flex;
		    align-items: center;
		    width: 100%;
		    text-transform: uppercase;
			transition:all .2s;
			box-sizing: border-box;
		   	position:relative;


			&:after{
			   	content:'';
			   	position:absolute;
			   	top:0;
			   	bottom:0;
			   	background: transparent;
			   	left: 30px;
			   	right: 30px;
				border-bottom: 1px solid #e6e8ea;
		   	}

		   	&:nth-of-type(1):before{
		   		content:'';
			   	position:absolute;
			   	top:0;
			   	bottom:0;
			   	background: transparent;
			   	left: 30px;
			   	right: 30px;
				border-top: 1px solid #e6e8ea;
		   	}

			&:hover{
			    background: #dfe4ea;
			    border-right: 5px solid #70a1ff;
			}

			p{
			    position:relative;
			    margin:0;
			    padding:0;
				span{
					height:15px;
					width:auto;
					padding:0 5px;
					font-size:12px;
					background:#da7079;
					position:absolute;
					color:#fff;
					top: -5px;
    				right: -11px;
					display:flex;
					justify-content: center;
					align-items: center;
					border-radius: 50%;

				}
			}

			&:nth-of-type(3):hover{
			    border-right: 5px solid #ff4757;
			}

		}
			
	}

	.conversation{
		display: flex;
	    width: 100%;
	    padding: 10px;
	    box-sizing: border-box;
	    transition:all .2s;
	    align-items: center;
	    cursor:pointer;
	    color:#57606f;
	    position:relative;
	   &:hover{
	   		background:#dfe4ea;

	   }

	   &:after{
	   	content:'';
	   	position:absolute;
	   	top:0;
	   	bottom:0;
	   	background: transparent;
	   	left: 10px;
	   	right: 10px;
		border-bottom: 1px solid #e6e8ea;
	   }
		
		h3{
		    font-weight:normal;
			margin:0 10px;	
		}

		div:nth-of-type(1){
			width: 100px;
			height:100px;
			border-radius:50%;
			position: relative;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			border-radius:50%;
			display:flex;
			width:50px;
			height:50px;

		
			img{
				height: 110%;
				width: auto;
			}
		}

		div:nth-of-type(2){
			display:flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 0 10px;
			width:60%;

			p{
				margin:0;
				width:100%;
				margin: 0;
			    white-space: nowrap;
			    text-overflow: ellipsis;
			    width: 100%;
			    overflow: hidden;
			}

			h3{
				width:100%;
			}
		}
	}

	.active-conversation{
	   	background:#dfe4ea !important;
	   	border-left: 5px solid #70a1ff;
	}
	
	.conversations-placeholder{
	    justify-content: center;
	    display: flex;
	    align-items: center;
	    flex-direction: column;
	    align-items: center;
	    padding:20px;
	    text-align: center;
	    height: 100%;
	    color: #57606f;

	    h3{
	        text-transform: none;
	    }

	    img{
	    	width:80px;
	    }
	}

	li{
		list-style:none;
	}

	#chat-preloader{
	    position: absolute;
	    top: 0;
	    bottom: 0;
	    margin: auto;
	    left: 0;
	    right: 0;

	}
</style>


<script>
	import axios from 'axios'

	axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
	export default {
		name:'chat',
		data(){
			return {
				token:'',
				userId:'',
				userName:'',
				userAvatar:'',
				tempUserAvatar:'',
				message: '',
				current_conversation: {
					messages: []
				},
				conversations:'',
				activeConversation:'',
				currentContact:'',
				keyword:'',
				searching:'',
				result:'',
				settings:false,
				password:'',
				newPassword:'',
				loading:false,
				chosen: false
			}
		},
		created(){
			this.token = localStorage.getItem('token');
		    Echo.connector.pusher.config.auth.headers['Authorization'] = 'Bearer '+this.token;
			var Ref= this;
			axios.all([
				axios.get('/api/user/info?token='+this.token),
				axios.get('/api/conversations?token='+this.token)
			  ])
			.then(axios.spread((userResponse, conversationsResponse) => {
				Ref.userId=userResponse.data.user.id;
				Ref.userName=userResponse.data.user.name;
				Ref.userAvatar=userResponse.data.user.avatar;
				Ref.tempUserAvatar=Ref.userAvatar;
				Ref.conversations=conversationsResponse.data.conversations;
			}))
			.catch(error=>{
				console.log(error);
			});

		},

		methods:{
			logout(){
				localStorage.removeItem('token');
				this.$router.push({name:'home'});
			},

			browseNewAvatar(){
				$('#avatar-input').click();
			},

			activate(id){
				this.activeConversation=id;
			},

			sendMessage(){
				var Ref=this;
				this.current_conversation.messages.unshift({
					body: this.message,
					author_id: this.userId
				});
				let messageToSend = this.message;
				this.message='';

				axios.post('/api/message/send?token='+this.token,
					{conversation_id: (Ref.current_conversation.messages.length!==0)? Ref.current_conversation.id : null , 
						body: messageToSend,
						message_to: this.currentContact.id})
				.then(response =>{
					if (Ref.current_conversation.messages.length ===0) Ref.getConversation(response.data.conversation_id,this.currentContact);

				})
				.catch(error =>{
					console.log(error);
				});
			},

			getConversation(id,user){
				this.loading=true;
				var Ref=this;
				this.currentContact=user;
				axios.post('/api/conversation/get?token='+this.token,
					{conversation_id: id})
				.then(response =>{
					this.chosen=true;
					this.loading=false;
					Ref.current_conversation=response.data.conversation;

					Echo.join("chat."+response.data.conversation.id)
					// .here()
					//    .joining()
					//    .leaving()
					.listen('MessageSent', e =>{
						this.current_conversation.messages.unshift(e.message);
					});

				})
				.catch(error =>{
					console.log(error);
				});
			},

			search(){
				if(this.keyword==='') return this.searching=false;

				this.searching=true;
				var Ref=this;
				axios.post('/api/user/find?token='+this.token,
					{keyword: this.keyword})
				.then(response =>{
					Ref.result=response.data.users;
				})
				.catch(error =>{
					console.log(error);
				});
			},

			getFoundConversation(user){
				this.searching=false;
				var Ref=this;
				this.keyword='';
				axios.post('/api/user/find/conversation?token='+this.token,
					{id: user.id})
				.then(response =>{
					this.currentContact=user;
					// if(response.data.conversation===null){
					// 	console.log(user);
					// }
					if(response.data.conversation) Ref.current_conversation=response.data.conversation;

				})
				.catch(error =>{
					console.log(error);
				})
			},

			changeAvatarInit(e){
				var fileReader = new FileReader();

				fileReader.readAsDataURL(e.target.files[0]);
				fileReader.onload= e =>{
					this.tempUserAvatar=e.target.result;
					$('#save-avatar').removeAttr('disabled');
				}
			},

			submitAvatarChange(){
				var Ref= this;
				axios.post('/api/user/avatar?token='+this.token,
				{
					avatar: this.tempUserAvatar,
					user_id: this.userId
				})
				.then(response=>{
					Ref.userAvatar=response.data.new_path;
				})
				.catch(error=>{
					console.log(error);
				});
			}
		}
	}
</script>