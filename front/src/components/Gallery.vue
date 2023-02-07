<template>
    <div>
        <Modal title="لیست فایل ها" width="w-[78%]" save="استفاده" ref="galleryModal" @callback="path.length > 0 ? usePath() : ''">
                <div class="search form-input">
                    <Input type="search" title="جستجو..." id="search" :required="false" @keyup="search($event)" />
                </div>
                <div class="other">
                    <div class="">
                        <Button text="ساخت فولدر" color="red" @click="showMakeFolder()"></Button>
                        <Button text="اضافه کردن رسانه" color="green" @click="showFile()"></Button>
                    </div>
                    <div class="backDir">
                      <span v-if='currentDirectory !== "/uploader/gallery/"' class="cursor-pointer" @click="backDirectory()">
                        <i  class="fas fa-arrow-alt-circle-left rem--7 arrow-left" ></i>
                      </span>
                      <span v-else>
                          <i  class="fas fa-arrow-alt-circle-left rem--7 arrow-left"></i>
                      </span>
                      <b class="!bg-[#1c1c22] text-crimson-200 !mt-2">{{currentDirectory}}</b>
                    </div>
                </div>
                <div class="gallery" v-if="galleries.length > 0">
                    <div class="gallery-list" v-for="(gallery, index) in galleries" :key="index">
                        <span v-if="(/\.(gif|jpe?g|tiff?|png|webp|bmp)$/i).test(gallery)" :class="['cursor-pointer']" @click="select($event,gallery)"><img  :src="url+gallery" width="120" height="120" alt=""></span>
                        <span v-else-if="(/\.(text|txt|csv|css)$/i).test(gallery)" :class="['cursor-pointer']"  @click="select($event,gallery)"><i class="fas fa-file-contract text-6xl fm:text-3xl"></i></span>
                        <span v-else-if="(/\.(mkv|mp4|avi|ogg)$/i).test(gallery)" :class="['cursor-pointer']" @click="select($event,gallery)"><i class="fas fa-file-video text-6xl fm:text-3xl"></i></span>
                        <span v-else-if="(/\.(srt|ssa|ttml|sbv|dfxp|vtt)$/i).test(gallery)" :class="['cursor-pointer']" @click="select($event,gallery)"><i class="fas fa-closed-captioning text-6xl fm:text-3xl"></i></span>
                        <span v-else-if="(/\.(apk)$/i).test(gallery)" :class="['cursor-pointer']"   @click="select($event,gallery)"> <i class="fas fa-android text-6xl fm:text-3xl"></i></span>
                        <span v-else-if="gallery.split('.').length <= 1" :class="['cursor-pointer']"   @click="showGallery(gallery)"><i class="fas fa-folder-open text-6xl fm:text-3xl" ></i></span>
                        <span v-else  :class="['cursor-pointer']" @click="select($event,gallery)"><i class="fas fa-question-circle text-6xl fm:text-3xl"></i></span>
                        <p class="caption rem--8 break-all w-[10rem]" v-text="showName(gallery)"></p>
                        <div class="trash gap-2">
                            <span @click="remove(gallery)"><i class="fas fa-trash text-2xl cursor-pointer text-red-500" title="حذف" ></i></span>
                            <span @click="copy(gallery)"><i class="fas fa-copy text-2xl cursor-pointer text-green-500" title="کپی مسیر" ></i></span>
                            <span @click="edit(gallery)"><i class="fas fa-edit text-2xl cursor-pointer text-blue-500" title="تغییر نام" ></i></span>
                        </div>
                    </div>
                </div>
        </Modal>

    <!-- Make Folder -->
        <Modal :title="rename ? 'تغییر نام' : 'ساخت فولدر'" width="w-[68%]" save="ذخیره" ref="folder" @callback="rename ? update() : makeFolder()">
            <div v-show="isModalMakeFolderVisible"><Error :errors="getErrors"></Error></div>
                <div class="form">
                    <div v-if="rename">
                        <div class="form-input mb-8 fm:mb-4">
                            <span class="font-1-4 rem-0-7"> نام قبلی: {{previous_name}} </span>
                        </div>
                        <div class="form-input">
                            <Input v-model="newName" title="نام جدید" id="newName" />
                        </div>
                    </div>
                    <div class="form-input" v-else>
                        <Input v-model="folderName" title="نام فولدر" id="folderName" />
                        <small class="text-red-500 text-sm rem--7">مسیر فعلی برای ساخت فولدر: {{currentDirectory}}</small>
                    </div>
                </div>
        </Modal>
    <!--    Insert Image-->
        <Modal title="اپلود رسانه" save="اپلود" width="w-[68%]" ref="upload" @callback="upload()">

            <div v-show="isModalFileVisible"><Error :errors="getErrors"></Error></div>
                <div class="column">
                <div class="form-control column">
                    <label for="file" class="rem--7">انتخاب تصویر یا ویدیو <span class="text-red-500 rem--7">*</span></label>
                    <Input @change="getFIle($event)" type="file" title="" id="file" :required="false" />

                    <div class="form-control flex flex-col">
                        <div class="relative pt-1" v-show="uploadPercentage > 0">
                            <div class="flex mb-2 items-center justify-between">
                                <div class="text-right">
                                    <span class="text-lg rem--7 font-semibold inline-block text-pink-600">
                                        {{ uploadPercentage }}%
                                    </span>
                                </div>
                            </div>
                            <div class="overflow-hidden h-2 mb-4 text-lg rem--7 flex rounded bg-pink-200">
                                <div :style="{'width': uploadPercentage+'%'}" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-pink-500"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>
        <Loading :loading="loading"></Loading>
    </div>
    
</template>
<script>
    import axios from "../plugins/axios";
    import Modal from "@/components/Modal";
    import Loading from "@/components/Loading";
    import Button from "@/components/Button";
    import Input from "@/components/Input";
    import Error from "./Error";
    import {mapGetters} from 'vuex'
    import Alert from "../plugins/alert";
    import swal from 'sweetalert2';
    window.Swal = swal;
    export default{
        name:"Gallery",
        props:{
            multiple:{
                type:Boolean,
                default:false
            }
        },
        components:{Modal,Error,Loading,Input,Button},
        data(){
            return{
                url:process.env.VUE_APP_API,
                isModalGalleryVisible: false,
                isModalMakeFolderVisible: false,
                isModalFileVisible: false,
                galleries:[],
                allData:[],
                currentDirectory:"",
                folderName:null,
                file:null,
                path:[],
                previous_name:null,
                newName:null,
                rename:false,
                loading:false,
                uploadPercentage:0
            }
        },
        mounted() {
            this.showGallery()
        },
        computed:{
          ...mapGetters(["getErrors"])
        },
        methods:{
            async showGallery(directory="/uploader/gallery/", loading = true){
                this.path = [];
                this.loading = loading;
                this.currentDirectory = directory;
                await axios.post(this.url+'/api/show-gallery',{directory:this.currentDirectory }).then(resp=>{
                    this.galleries = resp.data.data;
                    this.allData = this.galleries;
                })
                this.loading = false;
            },
            backDirectory(){
                let folders = this.currentDirectory.split('/');
                if(folders[folders.length -1] === ''){
                    folders.pop()
                }
                folders.pop()
                let previousDirectory = folders.join('/') + '/'
                this.currentDirectory = previousDirectory;
                this.showGallery(previousDirectory)
            },

            async makeFolder(){
                this.isModalMakeFolderVisible = true;
                this.isModalFileVisible = false;
                this.loading = true;
                let currentDirectory = this.fixDir()
                let dir = currentDirectory+this.folderName;
                let data = [
                    'make-folder',
                    {folder:dir,folderName:this.folderName}
                ];
                await this.$store.dispatch("insert",data)
                await this.showGallery(this.currentDirectory)
                this.loading = false;
                this.clearMakeFolder()
            },
            async upload(){
                this.isModalMakeFolderVisible = false;
                this.isModalFileVisible = true;
                this.loading = true;
                let formData = new FormData;
                formData.append('file',this.file);
                formData.append('folder',this.currentDirectory);
                let config = {
                    onUploadProgress: ( progressEvent ) => {
                        this.uploadPercentage = parseInt( Math.round( ( progressEvent.loaded / progressEvent.total ) * 100 ) );
                    }
                }
                let data=[
                    'gallery-upload',
                    formData,
                    config
                ]
                await this.$store.dispatch("insert",data)
                await this.showGallery(this.currentDirectory, false)
                this.loading = false;
            },
            async remove(gallery){
                await Swal.fire({
                    title: "آیا از حذف این ایتم مطمئنید؟",
                    text: 'چناچه ایتم مورد نظر ( فولدر ) باشد فولدر به همراه تمامی زیر مجوعه ها حذف میشود',
                    icon: "warning",
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'بله',
                    denyButtonText: `خیر`,

                }).then((willDelete) => {
                    if (willDelete.isConfirmed) {
                        axios.post(this.url+'/api/gallery-remove',{gallery:gallery}).then(resp=>{
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
                        })
                    }
                });
                await this.showGallery(this.currentDirectory)
            },
            select(event,gallery){
                let element = event.currentTarget;
                if(this.multiple){
                  if(this.path.includes(gallery)){
                    element.classList.value = ' ';
                    this.path = this.path.filter((item)=>{return item !== gallery})
                  }else{
                    this.path.push(gallery);
                    element.classList.add('my-border');
                  }
                }else{
                  this.removeBorders();
                  this.path = gallery;
                  element.classList.add('my-border');
                }
            },
            usePath(){
                this.$parent.filePath = this.path;
                this.path = [];
                this.removeBorders();
                this.$refs.galleryModal.toggleModal();
            },
            copy(gallery){
                navigator.clipboard.writeText(this.url+gallery);
                Alert.show('موفق','مسیر فایل کپی شد')
            },
            edit(gallery){
                this.emptyError();
                this.$refs.folder.toggleModal();
                this.rename = true
                this.previous_name = this.showName(gallery)
            },
            async update(){
                this.loading = true;
                // let currentDirectory = this.fixDir()
                let data = [
                    'gallery-rename',
                    {newName:this.newName,oldName:this.previous_name, current:this.currentDirectory}
                ];
                await this.$store.dispatch("insert",data)
                await this.showGallery(this.currentDirectory, false)
                this.loading = false;
                this.clearNewName()
            },
            search(event){
                this.galleries = this.allData;
                let txt = event.target.value;
                this.galleries = this.galleries.filter(gallery => gallery.includes(txt))
            },
            showName(name){
              let splitName = name.split('/');
              return splitName[splitName.length - 1];
            },
            fixDir(dir = this.currentDirectory){
              return (/^(.*[\/]$)/i).test(dir) ? dir : dir+'/';
            },
            toggleGalleryModal() {
                this.$refs.galleryModal.toggleModal();
            },
            closeMakeFolderModal() {
                this.$refs.folder.toggleModal();
            },
            closeFileModal() {
                this.$refs.upload.toggleModal();
            },
            clearMakeFolder(){
                this.folderName = null
            },
            clearNewName(){
                this.newName = null
            },
            getFIle(event){
                this.uploadPercentage = 0;
                this.file = event.target.files[0];
            },
            showMakeFolder(){
                this.emptyError();
                this.rename =false;
                this.$refs.folder.toggleModal();
            },
            showFile(){
                this.emptyError();
                this.$refs.upload.toggleModal();
            },
            emptyError(){
                this.$store.state.errors = [];
            },
            removeBorders(){
                let elems = window.document.querySelectorAll(".my-border")
                for(let i = 0; i < elems.length; i++){
                  elems[i].classList.remove("my-border")
                }
            },
            removeBorder(g){
                let el = window.document.getElementsByClassName(g)[0]
                if(el !== undefined){
                  el.classList.value = "cursor-pointer"
                }

            }
        }
    }
</script>
<style scoped>
    .progress{
        text-align: center;
    }
    progress{
        width: 100%;
        padding: 2rem;
    }
    .progress span{
        margin-top: 1rem;
        font-size: 1.4rem;
        color: blue;
    }
    .gallery{
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
    }
    .other{
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .buttons{
        border-bottom: 1px solid #dedede;
    }
    .other .backDir{
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-end;
    }
    .other .backDir b{
        font-size: 1.3rem;
        background: #efefef;
        padding: 0.2em;
        border-radius: 0.5rem;
    }
    .other .backDir .arrow-left{
        float: left;
        font-size: 3rem;
    }
    .gallery .gallery-list{
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 1rem;
    }
    .gallery .gallery-list img{
        cursor: pointer;
        max-width: unset;
        height: 120px;
    }
    .gallery .gallery-list .my-border{
        border: 2px solid red;
        border-radius: .4rem;
        padding: .3rem;
    }
    .gallery .gallery-list i {
        font-size: 12rem !important;
        color: #000000 !important;
    }
    .gallery .gallery-list i:hover {
       color: #000000 !important;
    }
    .gallery .gallery-list .caption{
        font-size: 1.1rem;
        padding-top:1rem;
        font-weight: 500 !important;
        color: #dc143c;
        direction: ltr !important;
    }
    .gallery .gallery-list .trash{
        display: flex;
        align-items: center;
        opacity: 0;
        z-index: 2;
        transition: .4s ease-in-out;
    }
    
    .gallery .gallery-list:hover .trash{
        opacity: 1;
    }
    .search{
        margin-bottom: 1rem;
    }
    .search .input{
        width: 100%;
    }
    @media only screen and (max-width: 768px) {
        .trash span i{
            font-size: .7rem !important;
        }
        .gallery .gallery-list img{
            height: 70px;
            width: 70px;
        }
        .other{
            flex-direction: column;

        }
        .other .backDir{
            width: 100%;
            margin: 1.5em 0;
        }
        .other .backDir b{
            font-size: .8rem;
        }
        .other .backDir .arrow-left{
            float: left;
            font-size: .8rem;
        }
    }
</style>