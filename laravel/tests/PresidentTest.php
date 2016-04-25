<?php

require_once(__DIR__.'\MainTest.php');

class PresidentTest extends MainTest{
  private function presidentLogin(){
    $this->visit('/president/login')
         ->type('test', 'username')
         ->type('test', 'password')
         ->press('เข้าสู่ระบบ');
  }

  public function testShowPresidentIndexPage(){
    $this->presidentLogin();
    if(Operation::isClubAudition(Session::get('club_code'))){
      $this->visit('/president')
           ->seePageIs('/president')
           ->see('นักเรียนที่ยืนยันชมรมเดิม')
           ->see('นักเรียนที่สมัครออดิชัน');
    }else{
      $this->visit('/president')
           ->seePageIs('/president')
           ->see('นักเรียนที่ยืนยันชมรมเดิม')
           ->see('นักเรียนที่สมัครชมรม');
    }
  }

  public function testShowListFromConfirm(){
    $this->presidentLogin();
    $this->visit('/president/confirmed')
         ->see('นา');
  }
}
