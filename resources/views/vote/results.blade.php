<!-- Create a new Blade view (results.blade.php) to display the voting results -->
@extends('layouts.app')

@section('title')
    Polls-Results
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
                    <a :href="getvoteLink()">{{__('Polls')}}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
                <li class="flex items-center">
                    <a href="#">{{__('Results')}}</a>
                    <svg class="fill-current w-3 h-3 mx-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path
                            d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z" />
                    </svg>
                </li>
            </ol>
        </section>
           <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <!-- Display Question Title, Description, etc. -->
        <div class="mb-6">
            <label class="block text-gray-700 text-lg font-bold mb-2 uppercase tracking-wide font-bold">
                {{ $question->title }}
            </label>
            <p class="text-gray-700 text-lg">{{ $question->description }}</p> 
        </div>
        <div class="mb-6">
                        <img :src="getImageSrc(question.image.filename)" alt="Question Image" class="rounded-lg mb-4" style="width:500px; height:auto;">
                    </div>
                    <template v-for="(option, index) in question.options" >
    <div class="mb-3">
        <div class="flex items-center">
            <div class="text-base font-medium" :class="`text-${getColor(index)}-700 dark:text-${getColor(index)}-500`">
                @{{ option.value }}
            </div>
            <div class="ml-3 w-full flex items-center">
                <div style="width:85%" class="bg-gray-200 rounded-full h-4 dark:bg-gray-700 mr-2">
                    <div :class="`bg-${getColor(index)}-600 h-4 text-xs font-medium p-0.5 text-${getColor(index)}-100 leading-none rounded-full dark:bg-${getColor(index)}-500`"
                        :style="{ width: getPercentage(option) + '%' }">
                    </div>
                </div>
                <div class="text-xs font-medium text-gray-600 dark:text-gray-400">
                    @{{ getPercentage(option) }}% ( @{{option.votes_count}} votes)
                </div>
            </div>
        </div>
    </div>
</template>

    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
    new Vue({
        el: '#app',
        data() {
            var question = {!! json_encode($question) !!};
            console.log(question);
            return {
                question: question, 
            };
        },
        methods: {
            getPercentage(option) {
                const totalVotes = this.question.votes_count; 
                return (((option.votes_count || 0) / totalVotes) * 100).toFixed(1);
            },
            getColor(index) {
        const colors = ['blue', 'red', 'green', 'yellow', 'indigo', 'purple']; // Add more colors if needed
        return colors[index % colors.length]; // Get color based on index, cycling through the array
    },
    getImageSrc(filename) {
                return "{{ asset('storage/images') }}/" + filename;
            },
            getMaxSrc() {
                return "{{ asset('storage/images') }}/max.png";
            },
            getvoteLink()
            {
                return (`{{ route('vote.vote', ['questionId' => ':questionId']) }}`.replace(':questionId',this.question.id));
            }
        },
    });
</script>
@endsection
