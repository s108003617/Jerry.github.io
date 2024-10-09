<?php
if(! isset($_SESSION)) {
  # 如果沒有設定 $_SESSION, 才啟動
  session_start();
}
$title = '登入';
$pageName = 'login';

?>
<?php include __DIR__ . '/parts/html-head.php' ?>
<?php include __DIR__ . '/parts/navbar.php' ?>
<style>
  .required {
    color: red;
  }

  .form-text {
    color: red;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-6">
      <div class="card">

        <div class="card-body">
          <h5 class="card-title">登入</h5>
          <form name="form1" onsubmit="sendData(event)">

            <div class="mb-3">
              <label for="email" class="form-label">帳號 (email)</label>
              <input type="text" class="form-control" id="email" name="email">
              <div class="form-text"></div>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">密碼</label>
                <input type="password" class="form-control" id="password" name="password">
                <div class="form-text"></div>
            </div>

            <button type="submit" class="btn btn-primary">登入</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">登入結果</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
          帳號或密碼錯誤
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續登入</button>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/parts/scripts.php' ?>
<script>
  const sendData = e => {
    e.preventDefault();
    const fd = new FormData(document.form1); // 建立一個只有資料的表單物件

    fetch('login-api.php', {
        method: 'POST',
        body: fd, // 預設的 Content-Type: multipart/form-data
      })
      .then(r => r.json())
      .then(data => {
        console.log(data);
        if (data.success) {
          // 成功登入後跳到首頁
          location.href = 'index_.php';
        } else {
          myModal.show();
        }
      }).catch(ex => {
        console.log(`fetch() 發生錯誤, 回傳的 JSON 格式是錯的`);
        console.log(ex);
      })

  }

  const myModal = new bootstrap.Modal('#exampleModal');
</script>
<?php include __DIR__ . '/parts/html-foot.php' ?>