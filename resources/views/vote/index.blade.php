@extends('layouts.app')
@section('title')
Polls-Main
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
        .w-85 {
        width: 85%;
        }
        body.modal-active {
            overflow-x: hidden;
            overflow-y: visible !important;
        }
        .card-image{
            max-width:300px;
            height:auto;
           justify-content:center;

        }
    </style>
@endsection
@section('content')
<div id="app">
    <div class="flex justify-center items-center mt-8 mb-4">
        <input type="text" v-model="searchQuery"
            class="border border-gray-400 p-2 rounded-l-lg focus:outline-none focus:ring focus:border-blue-500"
            placeholder="{{__('Search')}}...">
        <button @click="search"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r-lg">{{__('Search')}}</button>
    </div>
    <div class="flex justify-center">
        <div class="w-85">
    <div style="gap:30px;" class="flex flex-wrap justify-center items-center">
        <div  class="max-w-md mb-4"  v-for="(question, index) in questions">
            <div class="rounded overflow-hidden shadow-lg" style="width:300px; height:412px; text-align:center;">
                <div class="card-image mt-2" style="display: flex; justify-content: center; height:200px;">
                    <img :src="getImageSrc(question.image.filename)" alt="Card Image" class="card-image">
                </div>
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2">@{{question.title}}</div>
                    <p class="text-gray-700 text-base">@{{ question.description }}</p>
                </div>
                <div class="px-6 py-4">
                    <a :href="getVoteLink(question.id)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                        {{ __('VoteNow') }}
                    </a>
                </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>
</div>

@endsection



@section('js')
<script>
    new Vue({
        el: '#app',
        data(){
            var questions = {!! json_encode($questions) !!};
                
               return {
                   questions: questions,
                   searchQuery:"",
               }
           },
        methods:
        {
            getImageSrc(filename) {
    return "{{ asset('storage/images') }}/" + filename;
},
getVoteLink(questionId) {
                    return `{{ route('vote.vote', ['questionId' => ':questionId']) }}`.replace(':questionId', questionId);
            },
            search() {
                axios.get('/search', {
                    params: {
                        query: this.searchQuery
                    }
                })
                .then(response => {
                    this.questions = response.data;
                })
                .catch(error => {
                    console.error('There was an error!', error);
                });
            },
            async translateText(text) {
                try {
                    const response = await axios.get("{{ route('translate', ':text') }}".replace(":text",text));
                    console.log(response    );
                    return response.data.translatedText;
                } catch (error) {
                    console.log(error);
                    return text; // Return the original text if there's an error
                }
            },
        },
    });
</script>
@endsection