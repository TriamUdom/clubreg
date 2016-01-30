<!--
<div class="menu">
  <nav class='navbar navbar-custom navbar-fixed-top' role='navigation'>
    <div class='container'>
      <div class='navbar-header page-scroll'>
        <button type='button' class='navbar-toggle' data-toggle='collapse' data-target='.navbar-main-collapse'>
          <i class='fa fa-bars'></i>
        </button>
        <a class='navbar-brand' href='/'>
          clubs <span class='light'>triamudom</span>
        </a>
      </div>
      <div class='collapse navbar-collapse navbar-right navbar-main-collapse'>
        <ul class='nav navbar-nav'>
          <li class='hidden'><a href='/'></a></li>
          <li><a href='/'>หน้าหลัก</a></li>
          <li><a href='/test'>test</a></li>
          <li><a href='/clubs'>test2</a></li>
        </ul>
      </div>
    </div>
  </nav>
</div>

-->

<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand" href="/">ระบบลงทะเบียนชมรม โรงเรียนเตรียมอุดมศึกษา</a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-main">
      <ul class="nav navbar-nav">
      </ul>

      @if(false){
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" id="usermenu">{{ $fullname }} <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="usermenu">
                  <li><a href="/account">ประวัตินักเรียน (หน้าหลัก)</a></li>
                  <li class="divider"></li>
                  <li><a href="/contact">ติดต่อเจ้าหน้าที่</a></li>
                  <li class="divider"></li>
                  <li><a href="/logout">ออกจากระบบ</a></li>
                </ul>
              </li>
           </ul>
      @else
            <ul class="nav navbar-nav navbar-right">
              <li><a href="/login" target="_self">เข้าสู่ระบบ</a></li>
              <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" id="helpcenter">ศูนย์ช่วยเหลือ <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="helpcenter">
                  <li><a href="/faq">คำถามที่พบบ่อย</a></li>
                  <li><a href="/reset_password">ลืมรหัสผ่าน</a></li>
                  <li class="divider"></li>
                  <li><a href="/contact">ติดต่อเจ้าหน้าที่</a></li>
                </ul>
              </li>
            </ul>
      @endif

    </div>
  </div>
</div>
