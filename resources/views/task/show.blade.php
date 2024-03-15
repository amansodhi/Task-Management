<x-sub-section-panel sectionName="Task: {{ $task->title }}">
    {{-- show task section --}}
    <section>
        <div class="container">
            {{-- Action section --}}           
            @auth                
                <div class="flex flex-row-reverse space-x-reverse">
                    <form method="post" action="/task/{{ $task->id }}" onsubmit="return confirm('Please confirm task deletion')">
                        @csrf
                        @method('DELETE')
                        &nbsp; <button class="bg-red-500 h-10 w-10 rounded"><i class="fas fa-trash-alt fa-inverse"></i></button>
                    </form>
                    @if (!$task->is_completed) 
                        {{-- Notify user --}}                        
                        <button class="bg-green-500 h-10 w-10 rounded">
                            <a href="/task/{{ $task->id }}/notify">
                                <i class="fas fa-envelope fa-inverse"></i>
                            </a>
                        </button>&nbsp;
                        {{-- Edit task --}}
                        <button class="bg-blue-500 h-10 w-10">
                            <a href="/task/{{ $task->id }}/edit"> 
                                <i class="fas fa-edit fa-inverse"></i> 
                            </a>
                        </button>
                        {{-- Mark Complete --}}
                        <form method="post" action="/task/{{ $task->id }}/completed" onsubmit="return confirm('Please confirm task completion, notification will be sent')">
                            @csrf
                            @method('PATCH')
                            <button class="bg-blue-300 h-10 w-40 rounded">Mark Complete</button> &nbsp;
                        </form>
                    @else
                        <button class="bg-blue-500 h-10 w-300" disabled>Task Completed</button>
                    @endif
                </div>
            @endauth

            {{--Task summary section--}}
            <x-content-layout contentName="Date Created" contents="{{ date('d/m/Y', strtotime($task->created_at)) }}" />
            <x-content-layout contentName="Deadline" contents="{{ date('d/m/Y', strtotime($task->deadline)) }}" />
            <x-content-layout contentName="Created by" contents="{{ $task->getTaskCreatorUser() }}" />
            <x-content-layout contentName="Assigned to" contents="{{ $task->getAssignedUser() }}" />
            <x-content-layout contentName="Description" contents="{{ $task->description }}" />

        </div>
    </section>
    <hr class="bg-gray-500 my-5">
</x-sub-section-panel>
