<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">การเลือกชมรม ประจำปีการศึกษา 2559</a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-main">
      <ul class="nav navbar-nav">
      </ul>

      @if(Session::get('logged_in') == 1)
            <ul class="nav navbar-nav navbar-right">
              <li><a href="/contact" target="_self">ติดต่อเจ้าหน้าที่</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" id="usermenu">{{ Session::get('fullname') }} <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="usermenu">
                  <?php /*
                  <li><a href="/account">ประวัตินักเรียน (หน้าหลัก)</a></li>
                  <li class="divider"></li>
                  <li><a href="/contact">ติดต่อเจ้าหน้าที่</a></li>
                  <li class="divider"></li>
                  */ ?>
                  <li><a href="/logout">ออกจากระบบ</a></li>
                </ul>
              </li>
           </ul>
      @else
            <ul class="nav navbar-nav navbar-right">
              <li><a href="/contact" target="_self">ติดต่อเจ้าหน้าที่</a></li>
              <?php //<li><a href="/login" target="_self">เข้าสู่ระบบ</a></li> ?>
              <?php /*
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" id="helpcenter">ศูนย์ช่วยเหลือ <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="helpcenter">
                  <li><a href="/faq">คำถามที่พบบ่อย</a></li>
                  <li class="divider"></li>
                  <li><a href="/contact">ติดต่อเจ้าหน้าที่</a></li>
                </ul>
              </li>
              */?>
            </ul>
      @endif

    </div>
  </div>
</div>
