<template>
	<div class="chat-container">
		<div class="sidebar">
			<div class="avatar-container">
				<div class="avatar">
					<img src="https://www.instituteofhypnotherapy.com/wp-content/uploads/2016/01/tutor-8.jpg" alt="">
				</div>
			</div>
			<h3>{{userName}}</h3>
			<ul>
				<li><p>Unread Messages<span>3</span></p></li>
				<li><p>settings</p></li>
				<li @click="logout"><p>log out</p></li>
			</ul>
		</div>
		<div class="chat">
			<div class="chat-header">
				<h3>Jane Rose</h3>
			</div>
			<div class="chat-content" v-if="current_conversation">
				<div :class="[(message.author_id == 1)? 'own-message' : 'other-message', 'message']" v-for="message in current_conversation.messages" >
					<div class="chat-avatar">
						<img src="https://davidbruceblog.files.wordpress.com/2014/05/img_9760.jpg" alt="" v-if="message.author_id != 1">
					</div>
					<p>{{message.body}}</p>
				</div>
			</div>
			<div class="text-field">
				<textarea placeholder="Type in your message here..." v-model="message"></textarea>
				<button class="cool-btn" @click="sendMessage">Send</button>
			</div>
		</div>
		<div class="conversations">
			<div class="conversation" v-for="conversation in conversations" @click="getConversation(conversation.id)">
				<div>
					<img src="https://davidbruceblog.files.wordpress.com/2014/05/img_9760.jpg" alt="">
				</div>
				<div>
					<h3>{{conversation.users[0].name}}</h3>
					<p>{{conversation.messages[0].body}}</p>
				</div>
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


	.chat{
		flex:1;
		display:flex;
		flex-direction: column;
		justify-content: center;
		position: relative;
		width:auto;
		height:100%;
		padding: 0 10px;
	}

	.chat-content{
		display:flex;
		flex-direction: column-reverse;
		height:100%;
		width:100%;
		overflow-y:auto;

	}

	.own-message{
		justify-content: flex-end;
		p{
			background:#0097e6;
		}
	}

	.other-message{
		p{
			background:gray;
		}
	}

	.message{
		width:100%;
		display:flex;
		align-items:center;
		p{
			border-radius: 20px;
			padding: 7px 10px;
		    word-break: break-word;
			color:#fff;
			display: inline-block;
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
		height:100px;
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

			p{
				margin:0;
				width:100%;
			}

			h3{
				width:100%;
			}
		}
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
				userId:'',
				userName:'',
				userEmail:'',
				message: '',
				current_conversation:'',
				conversations:''
			}
		},
		created(){
			var token=localStorage.getItem('token');
			var base64Url = token.split('.')[1];
            var base64 = base64Url.replace('-', '+').replace('_', '/');
            console.log(JSON.parse(window.atob(base64)));


			var Ref= this;
			axios.all([
				axios.get('/api/user/info?token='+token),
				axios.get('/api/conversations?token='+token)
			  ])
			.then(axios.spread((userResponse, conversationsResponse) => {
				Ref.userId=userResponse.data.user.id;
				Ref.userName=userResponse.data.user.name;
				Ref.userEmail=userResponse.data.user.email;

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

			sendMessage(){
				let token= localStorage.getItem('token');
				axios.post('/api/message/send?token='+token,
					{conversation_id: this.current_conversation.id, body: this.message})
				.then(response =>{
					console.log(response);
				})
				.catch(error =>{
					console.log(error);
				});
			},

			getConversation(id){
				let token= localStorage.getItem('token');
				var Ref=this;
				axios.post('/api/conversation/get?token='+token,
					{conversation_id: id})
				.then(response =>{
					Ref.current_conversation=response.data.conversation;
				})
				.catch(error =>{
					console.log(error);
				});
			}
		}
	}
</script>