<?php
/**
 * @var question \App\Models\Question
 */
?>
@extends('layouts.app')
@section('title')
Polls- Listing
@endsection
@section('style')
    <!--Regular Datatables CSS-->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--Responsive Extension Datatables CSS-->
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">

    <style>
.round-small-img {
  width: 50px; /* Set your preferred width */
  height: 50px; /* Set your preferred height */
  border-radius: 50%; /* Creates a round shape */
  object-fit: cover; /* Maintain aspect ratio */
  border: 2px solid #fff; /* Optional - Add a border */
}
        .dataTables_wrapper select,
        .dataTables_wrapper .dataTables_filter input {
            color: #4a5568; 			/*text-gray-700*/
            padding-left: 1rem; 		/*pl-4*/
            padding-right: 1rem; 		/*pl-4*/
            padding-top: .5rem; 		/*pl-2*/
            padding-bottom: .5rem; 		/*pl-2*/
            line-height: 1.25; 			/*leading-tight*/
            border-width: 2px; 			/*border-2*/
            border-radius: .25rem;
            border-color: #edf2f7; 		/*border-gray-200*/
            background-color: #edf2f7; 	/*bg-gray-200*/
        }

        /*Row Hover*/
        table.dataTable.hover tbody tr:hover, table.dataTable.display tbody tr:hover {
            background-color: #ebf4ff;	/*bg-indigo-100*/
        }

        /*Pagination Buttons*/
        .dataTables_wrapper .dataTables_paginate .paginate_button		{
            font-weight: 700;				/*font-bold*/
            border-radius: .25rem;			/*rounded*/
            border: 1px solid transparent;	/*border border-transparent*/
        }

        /*Pagination Buttons - Current selected */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current	{
            color: #fff !important;				/*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06); 	/*shadow*/
            font-weight: 700;					/*font-bold*/
            border-radius: .25rem;				/*rounded*/
            background: #667eea !important;		/*bg-indigo-500*/
            border: 1px solid transparent;		/*border border-transparent*/
        }

        /*Pagination Buttons - Hover */
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover		{
            color: #fff !important;				/*text-white*/
            box-shadow: 0 1px 3px 0 rgba(0,0,0,.1), 0 1px 2px 0 rgba(0,0,0,.06);	 /*shadow*/
            font-weight: 700;					/*font-bold*/
            border-radius: .25rem;				/*rounded*/
            background: #667eea !important;		/*bg-indigo-500*/
            border: 1px solid transparent;		/*border border-transparent*/
        }

        /*Add padding to bottom border */
        table.dataTable.no-footer {
            border-bottom: 1px solid #e2e8f0;	/*border-b-1 border-gray-300*/
            margin-top: 0.75em;
            margin-bottom: 0.75em;
        }

        /*Change colour of responsive icon*/
        table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
            background-color: #667eea !important; /*bg-indigo-500*/
        }

    </style>
@endsection
@section('content')
<div class="container w-full md:w-4/5 xl:w-2/3  mx-auto px-2" id="app">
    <div id='recipients' class="p-8 mt-6 lg:mt-0 rounded shadow bg-white">
            <table v-if="questions.length > 0"  id="example" class="stripe hover" style="width:100%; padding-top: 1em;  padding-bottom: 1em;">
                <thead>
                    <tr>
                        <th data-priority="1">#</th>
                        <th data-priority="2">{{ __('Image') }}</th>
<th data-priority="3">{{ __('Question') }}</th>
<th data-priority="4">{{ __('Options') }}</th>
<th data-priority="5">{{ __('Votes') }}</th>
<th data-priority="6">{{ __('State') }}</th>
<th data-priority="7">{{ __('Edit') }}</th>
<th data-priority="8">{{ __('Delete') }}</th>
<th data-priority="9">{{ __('LockUnlock') }}</th>
                    </tr>
                </thead>
            <tbody>
                <tr class="text-center" v-for="(question, index) in questions">
                    <th scope="row">@{{ question.id }}</th>
                    <td>
                        <div class="col-12 text-center">
                        <img :src="getImageSrc(question.image.filename)" alt="Small Image" class="round-small-img">
                        </div>
                    </td>
                    <td>@{{ question.title }}</td>
                    <td>@{{ question.options_count }}</td>
                    <td>@{{ question.votes_count }}</td>
                    <td>
                        <span v-if="question.active" class="label label-danger">{{__(('Open'))}}</span>
                        <span v-else class="label label-danger">{{__(('Closed'))}}</span>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" :href="`/questions/${question.id}/edit`">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="#" @click.prevent="deletePoll(index)">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a class="btn btn-info btn-sm" href="#" @click.prevent="toggleLock(index)">
                            <i v-if="question.active" class="fa fa-lock" aria-hidden="true"></i>
                            <i v-else class="fa fa-unlock" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
        <small v-else>{{ __('NoPollFound') }}<a href="{{ route('questions.create') }}">{{ __('Now') }}</a></small>
    </div>
    <a href="questions/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 mt-5 rounded-full">
        {{ __('Create') }}
    </a>
</div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <!--Datatables -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-responsive/2.5.0/dataTables.responsive.min.js" integrity="sha512-DY4twak65A5MI1m/CEKadDVrb0O8p7pLluLAXvpg0FjuQ4ZSzKyfcUtkM+ek4fIVUeaD7+nsv9k+mzTcFsDXIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
       new Vue({
           el: "#app",
           data(){
               return {
                   questions: {!! json_encode($questions) !!},
               }
           },
           mounted(){
           $('#example').DataTable({
               responsive: true
           }).columns.adjust().responsive.recalc();
       },
           methods: {
            getImageSrc(filename) {
        return `${window.location.origin}/storage/images/${filename}`;
    },
    async confirmLock(message) {
  return new Promise((resolve) => {
    Swal.fire({
      title: message,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
      resolve(result.isConfirmed);
    });
  });
},
async  deletePoll(index) {
                const confirmed = await this.confirmLock('Do you really want to delete this poll?');
  
                  if (confirmed) {
                       const questionId = this.questions[index].id; // Assuming 'id' is the question's ID field
                       axios.delete("{{ route('questions.delete') }}", { data: { question_id: questionId } })
                           .then((response) => {
                               this.questions.splice(index, 1);
                           });
                   }
               },

               async toggleLock(index){
                if (!this.questions[index].active) {
                    this.unlock(index);
                    return;
                }

                this.lock(index)
               },
               
               async lock(index) {
            const confirmed = await this.confirmLock('Do you really want to lock this poll?');
  
               if (confirmed) {
                console.log("lock");
                   let questionId = this.questions[index].id; // Assuming 'id' is the identifier for the question
                   let formData = new FormData();
                    formData.append('question_id', questionId);
                   axios.post("{{route('questions.lock')}}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })    .then((response) => {
                        this.questions[index].active = false;
                       })
                       .catch((error) => {
                           // Handle error
                       });

               }
               else
               {
               }
           },

          async unlock(index) {
            const confirmed = await this.confirmLock('Do you really want to unlock this poll?');
  
  if (confirmed) {
                   let questionId = this.questions[index].id; // Assuming 'id' is the identifier for the question
                   let formData = new FormData();
                    formData.append('question_id', questionId);
                   axios.post("{{route('questions.unlock')}}", formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                       .then((response) => {
                        this.questions[index].active = true;
                       })
                       .catch((error) => {
                          console.log(error);
                       });

               }
           },
       }
       })
    </script>
@endsection
