import { createStore } from 'vuex'
import axios from "../plugins/axios";
import Alert from "../plugins/alert"
import Toast from "../plugins/toast"
export default createStore({
  state: {
    user:{
      token:localStorage.getItem('token'),
      access:localStorage.getItem('access'),
      id:localStorage.getItem('id'),
      name:localStorage.getItem('name'),
    },
    api:process.env.VUE_APP_API+'/api/',
    sidebar:true,
    b_header:true,
    clear:true,
    errors:[],
    allData:[],
    singleData:null,
    current:1,
    previous:"",
    next:null,
    total:1,
    allPermissions:[],
    isLoading:true,
    genreSwitch:'movie',
    genreisSwitched:false,
    showSearch:false,
    isModalVisible:true,
    isNumeric:false,
    metaDescription:'دانلود فیلم جدید، دانلود فیلم، دانلود سریال، دانلود رایگان فیلم و سریال، دانلود فیلم دوبله فارسی بدون سانسور,فیلم خارجی,فیلم ایرانی,فیلم هندی,سریال خارجی,سریال ایرانی,سریال هندی,سریال کره ای,انیمیشن,کودکان,انیمه,انیمه ژاپنی.',

  },
  mutations: {
    catchError(state,payload,commit){
      console.log(payload);
      Toast.show('خطا','عملیات با خطا مواجه شد','error')
      if(payload === undefined){
        console.log(payload);
      }else if(payload.status === 500){
        state.errors.push(['مشکلی در سرور پیش امده است لطفا صبر نمایید '])
      }else if(payload.status === 422 || payload.status === 413){
        state.errors = Object.values(payload.data.errors)
      }else if(payload.status === 403){
        Alert.show('هشدار','شما سطح دسترسی لازم را برای انجام این کار ندارید.','warning')
        window.location.href = "/"
      }
    },
    scrollModalTop(){
      // let modal = window.document.getElementById('modal');
      // if(modal !== null){
      //   modal.scrollTop = 0;
      // }

    },
    emptyStorage(state,payload){
      localStorage.setItem('token','')
      localStorage.setItem('access','')
      localStorage.setItem('id','')
      localStorage.setItem('name','')
      window.location.href='/login';
    },
    setStorage(state,payload){
      if(payload[0]){
        localStorage.setItem('token',payload[1].token);
      }
      localStorage.setItem('access',payload[1].access);
      localStorage.setItem('id',payload[1].id);
      localStorage.setItem('name',payload[1].name);

    },
    toEnglishDigits(state,payload){
      let keyCode = (payload.keyCode ? payload.keyCode : payload.which);
      let keyChar = payload.key;
      if (keyCode > 31) {
          if((keyCode < 97 || keyCode > 105) && (keyCode < 48 || keyCode > 57)){
              Toast.show('لطفا ار اعداد انگلیسی استفاده کنید')
              payload.preventDefault();
          }
          

          if(['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'].includes(keyChar)){
              Toast.show('لطفا ار اعداد انگلیسی استفاده کنید')
              payload.preventDefault();
          }
      }
    },
    setPaginate(state, payload){
      let data = payload.data;
      state.current = data.meta.current_page
      state.next = data.links.next;
      state.previous = data.links.prev;
      state.total = data.meta.total;
      // this.deleteDuplicateNotify(data.notifications)
    },
    withData(state, payload){
      let withData = payload.with
      for(let i = 0; i < withData.length; i++){
        state.allData[withData[i]] = payload.data[withData[i]]
      }
    },
    isNumeric(state,payload) {
      state.isNumeric =  /^-?\d+$/.test(payload);
    }
  },
  actions: {
    async registerAndLogin({commit,state},payload){
      await axios.post(state.api+payload[1],payload[0]).then(resp=>{
          commit('setStorage',[true,resp.data])
          if(payload[1] === 'register'){
              Alert.show('موفق','ثبت نام با موفقیت انجام شد')
          }else{
              Alert.show('موفق','ورود با موفقیت انجام شد')
          }
          window.location.href = "/dashboard"
      }).catch(err=>{
        commit('catchError',err.response)
      })
    },
    async logout({commit,state},payload){
      await axios.post(state.api+payload[1]).then(resp=>{
          commit('emptyStorage');
          window.location.href = "/"
      }).catch(err=>{
        commit('emptyStorage');
        window.location.href = "/"
      });
    },
    async checkToken({commit,state}){
      await axios.get(state.api+'check-token').then(resp=>{
        if(resp.data.status === 'error'){
          commit('emptyStorage')
        }else{
          if(resp.data.token_is_expire){
            commit('setStorage',[false,resp.data])
          }else{
            commit('emptyStorage')
          }
        }
      }).catch(err=>{
        commit('emptyStorage')
      });
    },
    async allData({commit,state},payload){
      await axios.get(state.api+payload[0]).then(resp=>{
        state.allData = resp.data.data;
        if(payload[1]){
          commit('setPaginate',resp)
        }

        if(payload[2] !== undefined){
          commit('withData',{with:payload[2],data:resp.data})
        }
      }).catch(err=>{
        state.isLoading = false;
        commit('catchError',err.response)
      });
    },
    async insert({commit,state,dispatch},payload){
      let config = payload[2] !== undefined ? payload[2] : {}
      await axios.post(state.api+payload[0],payload[1],config).then(resp=>{
        if(resp.data.status === 'success'){
          state.clear  = true;
          let msg = resp.data.msg;
          msg = msg !== null ? msg : 'با موفقیت ثبت شد'
          Alert.show('موفق',msg);
        }
      }).catch(err=>{
        state.isLoading = state.clear  = false;
        commit('catchError',err.response)
        commit('scrollModalTop')
      })
    },
    async show({commit,state},payload){
      await axios.get(state.api+payload[0]).then(resp=>{
        state.singleData = resp.data.data;
      }).catch(err=>{
        state.isModalVisible = state.isLoading  = false;
        commit('catchError',err.response)
      })
    },
    async update({commit,state},payload){
      await axios.patch(state.api+payload[0],payload[1]).then(resp=>{
        if(resp.data.status === 'success'){
          Alert.show('موفق','با موفقیت ویرایش شد','success')
        }
      }).catch(err=>{
        state.isLoading  = false;
        commit('catchError',err.response)
        commit('scrollModalTop')
      });
    },
    async updateImage({commit,state},payload){
      await axios.post(state.api+payload[0],payload[1]).then(resp=>{
        if(resp.data.status === 'success'){
          Alert.show('موفق','با موفقیت ویرایش شد','success')
        }
      }).catch(err=>{
        state.isLoading  = false;
        commit('catchError',err.response)
        commit('scrollModalTop')
      });
    },
    async search({commit,state},payload){
      await axios.post(state.api+payload[0],payload[1]).then(resp=>{
        state.allData = resp.data.data;
        if(payload[2] !== undefined){
          commit('withData',{with:payload[2],data:resp.data})
        }
      }).catch(err=>{
        state.isLoading  = false;
        commit('catchError',err.response)
      })
    },
    async deleteRecord({commit,state},payload){
      await Swal.fire({
        title: "آیا از حذف این ایتم مطمئنید؟",
        text: payload[1],
        icon: "warning",
        showDenyButton: true,
        showCancelButton: false,
        confirmButtonText: 'بله',
        denyButtonText: `خیر`,

      }).then((willDelete) => {
        if (willDelete.isConfirmed) {
          state.isLoading  = true;
          axios.delete(state.api+payload[0]).then(resp=>{
            if(resp.data.status === 'success'){
              Swal.fire({
                title:'ایتم مورد نظر با موفقیت حذف شد',
                icon: "success",
                showDenyButton: true,
                denyButtonText: `بستن`,
              });
            }else {
              Swal.fire({
                title:'حذف با خطا مواجه شد.',
                icon: "error",
                showDenyButton: true,
                denyButtonText: `بستن`,
              });
            }
          }).catch(err=>{
            state.isLoading = false;
            commit('catchError',err.response)
          })
        }
      });
    }
  },
  getters: {
    getErrors(state){
      return state.errors
    },
    getToken(state){
      return state.user.token;
    },
    getAllData(state){
      return state.allData;
    },
    getSingleData(state){
      return state.singleData
    },
    getIsNumeric(state){
      return state.isNumeric;
    },
    getGenres(){
      return [
        {'value':'درام','text':'درام'},
        {'value':'خانوادگی','text':'خانوادگی'},
        {'value':'جنگی','text':'جنگی'},
        {'value':'جنایی','text':'درام'},
        {'value':'ترسناک','text':'ترسناک'},
        {'value':'تاریخی','text':'تاریخی'},
        {'value':'اکشن','text':'اکشن'},
        {'value':'انیمیشن','text':'انیمیشن'},
        {'value':'درام','text':'درام'},
        {'value':'کوتاه','text':'کوتاه'},
        {'value':'کمدی','text':'کمدی'},
        {'value':'وسترن','text':'وسترن'},
        {'value':'ورزشی','text':'ورزشی'},
        {'value':'موزیک','text':'موزیک'},
        {'value':'مستند','text':'مستند'},
        {'value':'ماجراجویی','text':'ماجراجویی'},
        {'value':'فانتزی','text':'فانتزی'},
        {'value':'تخیلی','text':'تخیلی'},
        {'value':'علمی','text':'علمی'},
        {'value':'عاشقانه','text':'عاشقانه'},
        {'value':'زندگی نامه','text':'زندگی نامه'}];
    },
  }
})
