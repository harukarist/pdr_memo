  {{-- バリデーションエラー --}}
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="m-0">
        @foreach($errors->all() as $message)
          <li>{{ $message }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <form action="{{ route('tasks.create', ['project_id' => $project_id]) }}" method="POST">
    @csrf
    <div class="form-group mb-2">
      <label for="task_name"><i class="fas fa-plus mr-1" aria-hidden="true"></i>タスクを追加</label>
      <input type="text" class="form-control" name="task_name" id="task_name" value="{{ old('task_name') }}" autofocus/>
    </div>
    <div class="form-inline row">
      <div class="form-group form-inline col-auto">
        <label for="priority">優先度</label>
        <select name="priority" id="priority" class="form-control ml-2 @error('priority') is-invalid @enderror">
          @foreach(\App\Task::PRIORITY as $key => $val)
            <option
                value="{{ $key }}"
                {{ $key == old('priority') ? 'selected' : '' }}
            >
              {{ $val['priority_name'] }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="form-group form-inline col-auto">
        <label for="due_date">期限</label>
        {{-- <input type="text" name="due_date" id="flatpickr" class="form-control ml-2" value="{{ old('due_date') }}"> --}}
        <input type="date" name="due_date" id="due_date" class="form-control ml-2 @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">

      </div>
      <div class="form-group form-inline col-auto">
        <button type="submit" class="btn btn-primary">追加</button>
      </div>
    </div>
  </form>
