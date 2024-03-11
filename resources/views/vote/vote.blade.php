    @extends('layouts.app')

    @section('title')
    Polls-Main
    @endsection

    @section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.css"
        rel="stylesheet" />
    <style>
        /* Your custom styles here */
    </style>
    @endsection

    @section('content')
    <div id="app" class="container mx-auto mt-8">
        <section class="p-10 px-0">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="{{ route('vote.index') }}">{{__('Home')}}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
                <li class="flex items-center">
                    <a href="#">{{__('Polls')}}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
            </ol>
        </section>
        <div class="w-full">
            <!-- Voting content similar to creation page content -->
            <div v-if="question">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <!-- Question -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold"
                            for="question">
                            @{{ question.title }}
                        </label>
                    </div>
    
                    <!-- Question Image -->
                    <div class="mb-6">
                        <img :src="getImageSrc(question.image.filename)" alt="Question Image" class="rounded-lg mb-4" style="max-height:200px; width:auto;">
                    </div>
    
                    <!-- Description -->
                    <div class="mb-6">
                        <p class="text-gray-700 text-lg">@{{ question.description }}</p>
                    </div>
    
                    <!-- Options -->
                    <div class="flex flex-wrap mb-6">
                        <div v-for="(option, index) in question.options"
                            class="w-full flex items-center border-b border-b-2 border-teal-500 py-2">
                            <label class="block">
                                <input type="radio" :value="option.value" v-model="selectedOption">
                                <span class="ml-2">@{{ option.value }}</span>
                            </label>
                        </div>
                    </div>
    
                    <!-- Date of ending -->
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-gray-600 text-sm">
                            <template v-if="!isEnded()">
                            {{__('EndsOn')}}: @{{ remainingTime }}
                            </template>
                            <template v-else>
                                {{__('PollEnded')}}
                            </template>
                        </p>
                    </div>
    
                    <div class="flex items-center justify-center">
                        <template>
                            <div>
                                <template v-if="!isEnded()">
                                    <template v-if="question.active">
                                        <button @click="vote" v-if="!userVoted"
                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                                            {{__('VoteNow')}}
                                        </button>
                                        <p v-else class="text-red-500 font-bold">{{__('YouVoted')}}</p>
                                    </template>
                                    <template v-else>
                                        <p class="text-red-500 font-bold">{{__('VoteClosed')}}</p>
                                    </template>
                                </template>
                                <template v-else>
                                    <p class="text-red-500 font-bold">{{__('PollEnded')}}</p>
                                </template>
                            </div>
                        </template>
                        <div class="bg-gray-200 rounded-full h-10 ml-10 px-4 flex items-center">
                            <a :href="viewResults()" class="text-blue-700 font-bold">
                            {{__('ViewResults')}}
                            </a>
                        </div>
                        </div>
                </div>
            </div>
            <div v-else>
                <p>{{__('NoQuestions')}}.</p>
            </div>
        </div>
    </div>
    @endsection

    @section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script>
        Vue.use(Toasted)
        new Vue({
            el: '#app',
            data() {
                var question = {!! json_encode($question)!!}
                var userHasVoted = {!! json_encode($userHasVoted)!!}
            console.log(question);
            return {
                question: {
                    id: question.id,
                    title: question.title,
                    description: question.description,
                    image: question.image,
                    model: question,
                    active: question.active,
                    startDate: question.start_at,
                    endDate: new Date(question.end_at),
                    options: question.options.map(option => ({
                        value: option.value,
                        placeholder: '',
                    })),
                },
                userVoted: userHasVoted,
                selectedOption:'',
                remainingTime: moment(new Date(question.end_at)).fromNow(),
            };
        },
            mounted() {
        },
            computed: {
        },

            methods: {
            getImageSrc(filename) {
                return "{{ asset('storage/images') }}/" + filename;
            },
            isEnded()
            {
                var a = moment(this.remainingTime, 'YYYY-MM-DD HH:mm:ss').isBefore(moment());
                console.log(a);
                return a;
            },
            vote() {
                if (!this.selectedOption) {
                    this.flushModal("{{__('PleaseSelect')}}", 2000);
                    return;
                }
    
                let formData = new FormData();
                formData.append('question_id', this.question.id);
                formData.append('option', this.selectedOption); // Include the selected option in the form data
                console.log(this.selectedOption);
                axios.post("{{route('vote.submit')}}", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((response) => {
                        // Handle successful vote submission
                        Vue.toasted.success(response.data).goAway(1500);
                        setTimeout(() => {
                           window.location.replace(this.viewResults());
                        }, 1500)
                    })
            },
            viewResults()
            {
                return (`{{ route('vote.results', ['questionId' => ':questionId']) }}`.replace(':questionId',this.question.id));
            },

        }
            });
    </script>
    @endsection