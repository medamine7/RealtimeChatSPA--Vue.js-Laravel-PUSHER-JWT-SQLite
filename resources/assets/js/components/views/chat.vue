<template>
	<div class="chat-container">
		<div class="sidebar">
			<div class="avatar-container">
				<div class="avatar">
					<img :src="userAvatar" alt="">
				</div>
			</div>
			<h3>{{userName}}</h3>
			<ul>
				<li><p>Unread Messages<span>3</span></p></li>
				<li><p>settings</p></li>
				<li @click="logout"><p>log out</p></li>
			</ul>
		</div>
		<div class="chat" v-if="current_conversation">
			<div class="chat-header">
				<h3>{{current_conversation.users[0].name}}</h3>
			</div>
			<div class="chat-content" v-if="current_conversation">
				<div :class="[(message.author_id == userId)? 'own-message' : 'other-message', 'message']" v-for="message in current_conversation.messages" >
					<div class="message-wrapper">
						<div class="chat-avatar">
							<img :src="current_conversation.users[0].avatar" alt="" v-if="message.author_id != userId">
						</div>
						<p>{{message.body}}</p>
					</div>
				</div>
			</div>
			<div class="text-field">
				<textarea placeholder="Type in your message here..." v-model="message" @keydown.enter.prevent="sendMessage"></textarea>
				<button class="cool-btn" @click="sendMessage">Send</button>
			</div>
		</div>
		<div class="chat-placeholder" v-if="!current_conversation">
			<img src="/img/paper-plane.svg" alt="">
			<h1>Choose a convertsation</h1>
		</div>
		<div class="conversations">
			<div class="conversations-wrapper" v-if="!searching">
				<div :class="['conversation', {'active-conversation' : (activeConversation==conversation.id)}]" v-for="conversation in conversations" @click="getConversation(conversation.id);activate(conversation.id)">
					<div>
						<img :src="conversation.users[0].avatar" alt="">
					</div>
					<div>
						<h3>{{conversation.users[0].name}}</h3>
						<p>{{conversation.last_message.body}}</p>
					</div>
				</div>
			</div>
			<div class="conversations-wrapper search-results" v-if="searching">
				<div v-for="user in result" class="conversation" @click="getFoundConversation(user.id)">
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
			width: 110%;
			height: auto;
		}
	}


	.chat, 
	.chat-placeholder{
		flex:1;
		display:flex;
		justify-content: center;
		position: relative;
		align-items: center;
		width:auto;
		height:100%;
		padding: 0 10px;
		flex-direction: column;
	}


	.chat-placeholder{
		color:#57606f;
		img{
			height:auto;
			width:100px;
		}
	}


	.chat-content{
		height:100%;
		width:100%;
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
		overflow: hidden;
		display:flex;
		justify-content: center;
		align-items: center;

		img{
			width: 110%;
			height: auto;
		}
	}


	.text-field{
		display: flex;
		width:100%;
		align-items:center;
		min-height: 100px;
		margin-top:20px;
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
		width:100%;
		height:100px;

		h3{
			text-transform:capitalize;
		}
	}

	.sidebar{
		justify-content: center;

		h3{
			text-transform: capitalize;
		}

		ul{
			display:flex;
			justify-content: center;
			align-items: center;
			width:100%;
			color:#57606f;
		    background: #dfe4ea;
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
		    	background:#ff4757;
			}

		    &:hover{
		    	background:#70a1ff;
		    	color:#fff;
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
				width: 110%;
				height: auto;
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
	

	li{
		list-style:none;
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
				message: '',
				current_conversation:'',
				conversations:'',
				activeConversation:'',
				keyword:'',
				searching:'',
				result:''
			}
		},
		created(){
			this.token = localStorage.getItem('token');
			var Ref= this;
			axios.all([
				axios.get('/api/user/info?token='+this.token),
				axios.get('/api/conversations?token='+this.token)
			  ])
			.then(axios.spread((userResponse, conversationsResponse) => {
				Ref.userId=userResponse.data.user.id;
				Ref.userName=userResponse.data.user.name;
				Ref.userAvatar=userResponse.data.user.avatar;

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

			activate(id){
				this.activeConversation=id;
			},

			sendMessage(){
				var Ref=this;
				axios.post('/api/message/send?token='+this.token,
					{conversation_id: this.current_conversation.id, body: this.message})
				.then(response =>{
					if (response.status==201) Ref.message='';
				})
				.catch(error =>{
					console.log(error);
				});
			},

			getConversation(id){
				var Ref=this;
				axios.post('/api/conversation/get?token='+this.token,
					{conversation_id: id})
				.then(response =>{
					Ref.current_conversation=response.data.conversation;
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
					this.result=response.data.users;
				})
				.catch(error =>{
					console.log(error);
				});
			},

			getFoundConversation(id){
				this.searching=false;
				var Ref=this;
				axios.post('/api/user/find/conversation?token='+this.token,
					{id: id})
				.then(response =>{
					this.current_conversation=response.data.conversation;
				})
				.catch(error =>{
					console.log(error);
				})
			}
		}
	}
</script>