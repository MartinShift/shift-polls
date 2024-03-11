@extends('layouts.app')
@section('title')
Polls- Creation
@endsection
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css" rel="stylesheet" />
    <style>
        .custom-label input:checked + svg {
            display: block !important;
        }

        .modal {
            transition: opacity 0.25s ease;
        }
        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
    </style>
@endsection
@section('content')
<div class="container mx-auto" id="app">
    <section class="p-10 px-0">
        <ol class="list-none p-0 inline-flex">
            <li class="flex items-center">
                <a href="{{ route('questions.index') }}">{{__(('Home'))}}</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li class="flex items-center">
                <a href="{{ route('questions.index') }}">{{__(('Polls'))}}</a>
                <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/></svg>
            </li>
            <li>
                <a href="#" class="text-gray-500" aria-current="page">{{__(('Create'))}}</a>
            </li>
        </ol>
    </section>
    <div class="w-full">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">

            <div class="mb-6">
                <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="question">
                {{__(('Question'))}}
                </label>
                <input v-model="question" placeholder="question" class="shadow appearance-none border rounded w-full py-4 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="text">
            </div>
            <div class="mb-6">
            <label for="question-logo" class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold">
            {{__(('QuestionLogo'))}}
</label>
<input @change="onFileChange" placeholder="question image" class="shadow appearance-none border rounded w-full py-4 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="question-logo" type="file">

            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="question">
                {{__(('Description'))}}
                </label>
                <textarea v-model="description" placeholder="description" class="shadow appearance-none border rounded w-full py-4 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="description">
                </textarea>
            </div>
            <div class="flex flex-wrap mb-6">
                <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold" for="options">
                {{__(('Options'))}}
                </label>
                <div v-for="(option, index) in options" class="w-full flex items-center border-b border-b-2 border-teal-500 py-2">
                    <input v-model="option.value" :placeholder="option.placeholder" class="appearance-none bg-transparent border-none block w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" />
                    <button @click.prevent="remove(index)" class="flex-shrink-0 border-transparent border-4 text-teal-500 hover:text-teal-800 text-sm py-1 px-2 rounded" type="button">
                    {{__(('Remove'))}}
                    </button>
                </div>
                <div class="w-full flex items-center border-b border-b-2 border-teal-500 py-2">
                    <input @keyup.enter="addNewOption" v-model="newOption" class="appearance-none bg-transparent border-none block w-full text-gray-700 mr-3 py-1 px-2 leading-tight focus:outline-none" type="text" placeholder="option text" aria-label="Full name">
                    <button @click.prevent="addNewOption" class="flex-shrink-0 bg-teal-500 hover:bg-teal-700 border-teal-500 hover:border-teal-700 text-sm border-4 text-white py-1 px-2 rounded" type="button">
                    {{__(('Add'))}}
                    </button>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                    {{__(('EndsAt'))}}
                    </label>
                    <input id="ends_at" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500 localDates" type="text">
                </div>
            </div>
            <div class="flex items-center justify-between">
                <button @click.prevent="save" class="bg-teal-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                {{__(('Create'))}}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script>
        Vue.use(Toasted)
        new Vue({
           el: '#app',
            computed:{
              filledOptions(){
                return this.options.map((option) => {
                    return option.value;
                } ).filter((option) => option);
              }
            },
            mounted(){
                $('.localDates').datetimepicker({
                    format: 'y-m-d H:m',
                });
            },
            data(){
               return {
                    newOption: '',
                    question: '',
                    options: [
                        { value: '', placeholder: "{{__(('OptionText'))}}"},
                        { value: '', placeholder: "{{__(('OptionText'))}}"},
                    ],
                   error_message: '',
                   questionImage: null,
                   description: '',
               }
            },
            methods:{
                onFileChange(event) {
            this.questionImage = event.target.files[0]; 
        },
               addNewOption(){
                   if(this.newOption.length === 0){
                       this.error_message = "{{__(('PleaseFill'))}}";
                       this.flushModal();
                       return;
                   }
                   if(this.filledOptions.filter( option => option === this.newOption).length !== 0){
                       this.error_message = "{{__(('CantUse'))}}";
                       this.flushModal();
                       return;
                   }

                   this.options.push({
                       value: this.newOption,
                       placeholder: ''
                   });
                   this.newOption = '';
               },
                remove(index){
                    if(this.filledOptions.length <= 2){
                        this.error_message = "{{__(('TwoOptions'))}}";
                        this.flushModal();
                        return;
                    }
                    this.options = this.options.map((option, localIndex) => {
                        if(localIndex === index){
                            return null;
                        }

                        return option
                    }).filter(option => option);
                },
                save(){
                    if(this.question.length === 0){
                        this.error_message = "{{__(('PleaseFill'))}}";
                        this.flushModal();
                        return;
                    }

                    if(this.filledOptions.length < 2){
                        this.error_message = "{{__(('TwoOptions'))}}";
                        this.flushModal();
                        return;
                    }

                   let data = {
                    question: this.question,
                       options: this.filledOptions,
                   };

                   if(document.getElementById('ends_at').value !== ''){
                       data.end_at = document.getElementById('ends_at').value;
                   }
               
                   let formData = new FormData();
                    formData.append('title', data.question);
                formData.append('description', this.description);
                formData.append('start_at', new Date().toISOString());
                formData.append('end_at', data.end_at);
                formData.append('image', this.questionImage);
                this.options.forEach((option, index) => {
                formData.append(`options[${index}]`, option.value);
                });
                    axios.post("{{route('questions.store')}}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                        .then((response) => {
                            Vue.toasted.success('Question Added!').goAway(1500);
                            setTimeout(() => {
                                window.location.replace("{{ route('questions.index') }}");
                            }, 1500)
                        })
                        .catch((error) => {

                            Object.values(error.response.data.errors)
                                .forEach((error) => {
                                    this.flushModal(error[0], 2000);
                                })
                        })
                },
                flushModal(message = this.error_message, after = 1500){
                    Vue.toasted.error(message).goAway(after);
                }
            }
        });
    </script>
@endsection
