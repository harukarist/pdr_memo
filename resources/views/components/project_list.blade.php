<section class="row justify-content-center">
  <div class="col-sm-8">
    <div class="card">
      <div class="card-header">プロジェクト一覧</div>
      <div class="card-body">
        <table class="table">
          <tbody>
            @foreach($projects as $project)
              <tr>
                <td>{{ $project->project_name }}</td>
                <td>
                    <a href="{{ route('projects.edit', ['project_id' => $project->id]) }}" class="btn btn-primary">編集</a>
                <td>
                  <form action="{{ route('projects.delete', ['project_id' => $project->id]) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <button class="btn btn-danger" onclick='return confirm("削除しますか？");'>削除</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </form>
      </div>
    </div>
  </section>
  <section class="row justify-content-center">
    <a href="{{ route('home') }}" class="c-navbar__item nav-item nav-link">タスクリストに戻る</a>
  </section>
</div>
