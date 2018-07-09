import Vuex from 'vuex'
import Vue from 'vue'


Vue.use(Vuex);


export default new Vuex.Store({
	state:{
		loginModal: false,
		registerModal: false,
	},
	getter:{

	},
	mutations:{
		OPEN_LOGIN : state =>{
			state.loginModal=true;
		},
		OPEN_REGISTER : state =>{
			state.registerModal=true;
		},
		CLOSE_LOGIN : state =>{
			state.loginModal=false;
		},
		CLOSE_REGISTER : state =>{
			state.registerModal=false;
		}

	},
	actions:{
		openLogin: context =>{
			context.commit('OPEN_LOGIN');
		},
		openRegister: context =>{
			context.commit('OPEN_REGISTER');
		},
		closeLogin: context =>{
			context.commit('CLOSE_LOGIN');
		},
		closeRegister: context =>{
			context.commit('CLOSE_REGISTER');
		}
	}
});