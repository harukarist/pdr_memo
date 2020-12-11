  <div class="card">
    <div class="card-header">カテゴリーリスト</div>
    <div class="card-body">
      <table class="table">
        <tbody>
          @foreach($categories as $category)
            <tr>
              <td>{{ $category->category_name }}</td>
              <td>
                  <a href="{{ route('categories.edit', ['category_id' => $category->id]) }}" class="btn btn-primary">編集</a>
              <td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </form>
    </div>
  </div>
