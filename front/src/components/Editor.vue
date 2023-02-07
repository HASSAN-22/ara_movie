<template>
    <div class="block ltr">
        <QuillEditor
            ref="editor"
            class="quill-editor"
            :placeholder="placeholder"
            :options="options"
            :v-model="modelValue"
            @ready="onEditorReady($event)"
            @update:content="$emit('update:modelValue', contentType === 'text' ? this.getTEXT() : this.getHTML())"
        />
    </div>
</template>

<script>
import {defineComponent} from 'vue'
import {QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default defineComponent({
    components: {QuillEditor},
    props: {
        modelValue: String,
        placeholder: {
            type: String,
            default: "نظر خود را بنویسید.."
        },
        contentType: {
            type: String,
            default: "text"
        }
    },
    emits: ['update:modelValue'],
    computed: {
        options() {
            return {
                theme: 'snow',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                        ['blockquote', 'code-block'],

                        [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                        [{ 'direction': 'rtl' }],                         // text direction

                        [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                        [{ 'font': [] }],
                        [{ 'align': [] }],

                        ['clean']                                         // remove formatting button
                    ]
                }
            }
        },
        editor() {
            return this.$refs.editor.getQuill()
        }
    },
    mounted() {
        this.editor.format('direction', 'rtl');
        this.setHTML(this.modelValue);
    },
    methods: {
        getHTML() {
            return this.$refs.editor.getHTML();
        },
        getTEXT() {
          return this.$refs.editor.getText();
        },
        setHTML(value) {
            return this.$refs.editor.setHTML(value);
        },
        onEditorReady(editor) {
            editor.getModule('toolbar').addHandler('image', () => this.imageHandler(editor));
        },
        imageHandler: async function (editor) {
            const input = document.createElement('input');

            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                const formData = new FormData();

                formData.append('file', file);

                // Save current cursor state
                const range = editor.getSelection(true);

                let toast = this.$toasted.info('حذف تصویر فعلی...', {
                    duration: null
                });

                await axios.post("/admin/media", formData)
                    .then(async ({data}) => {
                        // Insert uploaded image
                        await editor.insertEmbed(range.index, 'image', data.link);
                    })
                    .catch(({response}) => {
                        this.$toasted.error('یک مشکل غیرمنتظره رخ داد ');
                    })
                    .finally(() => {
                        toast.remove();
                    })
            }
        },
    },
})
</script>

<style>
.ql-editor {
    direction: rtl;
    text-align: right;
    font-size: initial;
}

.quill-editor img {
    max-width: 100%;
    height: auto;
}

.ql-toolbar.ql-snow .ql-formats {
    margin-right: 0;
    margin-left: 0;
    margin-inline-end: 15px;
}

.ql-snow .ql-tooltip {
    z-index: 99999;
}

.ql-snow .ql-toolbar .ql-formats {
    margin: 8px;
}

.ql-snow .ql-toolbar .ql-formats:first-child {
    margin-inline-start: 12px;
}

.ql-snow .ql-editor pre.ql-syntax {
    direction: ltr;
    text-align: left;
}
</style>