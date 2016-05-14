<?php namespace App\President;

use DB;
use Crypt;
use Input;
use Config;
use Session;
use Redirect;
use Operation;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Exceptions\DataException;

class President{

  /**
   * Authenticate the president
   *
   * @param mixed $username
   * @param mixed $password
   * @return bool
   */
  public function authenticatePresident($username, $password){
    if($this->presidentExist($username)){
      $data = DB::table('president')->where('username',$username)->first();
      $pepper = Config::get('applicationConfig.pepper');
      if(sha1($data->salt . $password . $pepper) == $data->password){
        // Auth Successful
        // Laravel's Session Magic. Do Not Touch.
        //Session::put('username', $user->national_id);
        Session::put('president_logged_in', '1');
        $club = DB::table('club')->where('club_code', $data->club_code)->pluck('club_name');
        Session::put('fullname', $club);
        Session::put('club_code',$data->club_code);

        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($username, $ip_address, "1", $useragent); // Success
        }

        return true;
      }else{
        if(Config::get('applicationConfig.environment') != 'testing'){
          // Log the request
          $ip_address = $_SERVER['REMOTE_ADDR'];
          $useragent = $_SERVER['HTTP_USER_AGENT'];
          $this->logAuthenticationAttempt($username, $ip_address, "0", $useragent); // Login Failed
        }
        return false;
      }
    }else{
      if(Config::get('applicationConfig.environment') != 'testing'){
        // Log the request
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $this->logAuthenticationAttempt($username, $ip_address, "0", $useragent); // Login Failed
      }
      return false;
    }
  }

  /**
   * Log user's login attempt
   *
   * @param mixed $nationalid
   * @param mixed $ip_address
   * @param bool $success whether or not the attempt success
   * @param string $useragent useragent
   * @return bool
   */
  private function logAuthenticationAttempt($username, $ip_address, $success, $useragent){
      $id = DB::table('login_log')->insertGetId(array(
          'unix_timestamp' => time(),
          'username' => $username,
          'type' => 'president',
          'ip_address' => $ip_address,
          'success' => $success,
          'useragent' => $useragent
      ));

      if ($id != 0) {
          return true;
      } else {
          return false;
      }

  }

  /**
   * Check if president exist
   *
   * @param mixed $username
   * @return bool
   */
  public function presidentExist($username){
    if(DB::table('president')->where('username', $username)->exists()){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Check if president already logged in
   *
   * @return bool
   */
  public static function presidentLoggedIn(){
    if(Session::get('president_logged_in') == 1){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Encrypt nationalid from array of standard object that we get from DB->get()
   *
   * @param   array   plain text array
   * @return  array   encrypted array
   */
  private static function encryptNationalID(array $data){
    for($i=0;$i<count($data);$i++){
      $data[$i]->national_id = Crypt::encrypt($data[$i]->national_id);
    }

    return $data;
  }

  /**
   * Get data of pending auditioner
   *
   * @return object data
   */
  public function getAuditionData(){
    $data = DB::table('audition')
              ->join('user_year', function($join){
                $join->on('audition.national_id', '=', 'user_year.national_id')
                     ->on('audition.year', '=', 'user_year.year');
              })
              ->join('user', 'audition.national_id', '=', 'user.national_id')
              ->where('audition.year', Config::get('applicationConfig.operation_year'))
              ->where('audition.club_code', Session::get('club_code'))
              ->where('audition.status', 0)
              ->orderBy('user_year.class', 'asc')
              ->orderBy('user_year.room', 'asc')
              ->orderBy('user_year.number', 'asc')
              ->get();

    $data = self::encryptNationalID($data);

    return $data;
  }

  /**
   * Get data of passed auditioner
   *
   * @return object data
   */
  public function getAuditionPassed(){
    $data = DB::table('audition')
              ->join('user_year', function($join){
                $join->on('audition.national_id', '=', 'user_year.national_id')
                     ->on('audition.year', '=', 'user_year.year');
              })
              ->join('user', 'audition.national_id', '=', 'user.national_id')
              ->where('audition.year', Config::get('applicationConfig.operation_year'))
              ->where('audition.club_code', Session::get('club_code'))
              ->where('audition.status', 1)
              ->orderBy('user_year.class', 'asc')
              ->orderBy('user_year.room', 'asc')
              ->orderBy('user_year.number', 'asc')
              ->get();

    $data = self::encryptNationalID($data);

    return $data;
  }

  /**
   * Get data of failed auditioner
   *
   * @return object data
   */
  public function getAuditionFailed(){
    $data = DB::table('audition')
              ->join('user_year', function($join){
                $join->on('audition.national_id', '=', 'user_year.national_id')
                     ->on('audition.year', '=', 'user_year.year');
              })
              ->join('user', 'audition.national_id', '=', 'user.national_id')
              ->where('audition.year', Config::get('applicationConfig.operation_year'))
              ->where('audition.club_code', Session::get('club_code'))
              ->where('audition.status', -1)
              ->orderBy('user_year.class', 'asc')
              ->orderBy('user_year.room', 'asc')
              ->orderBy('user_year.number', 'asc')
              ->get();

    $data = self::encryptNationalID($data);

    return $data;
  }

  /**
   * Get data of confirmed auditioner
   *
   * @return object data
   */
  public function getAuditionConfirmed(){
    $data = DB::table('audition')
              ->join('user_year', function($join){
                $join->on('audition.national_id', '=', 'user_year.national_id')
                     ->on('audition.year', '=', 'user_year.year');
              })
              ->join('user', 'audition.national_id', '=', 'user.national_id')
              ->where('audition.year', Config::get('applicationConfig.operation_year'))
              ->where('audition.club_code', Session::get('club_code'))
              ->where('audition.status', 2)
              ->orderBy('user_year.class', 'asc')
              ->orderBy('user_year.room', 'asc')
              ->orderBy('user_year.number', 'asc')
              ->get();

    $data = self::encryptNationalID($data);

    return $data;
  }

  /**
   * Do audition action
   *
   * @return string on success
   * @return false on failure
   */
  public function auditionAction(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    $national_id = Crypt::decrypt($national_id_encrypted);

    if(DB::table('audition')->where('national_id', $national_id)->where('status', 2)->exists()){
      return Redirect::to('/president/audition')->with('error','ผู้ใช้นี้มีชมรมแล้ว');
    }else{
      switch($action){
        case 'confirm':
          //Get user into our club
          DB::table('audition')
            ->where('national_id', $national_id)
            ->where('club_code', Session::get('club_code'))
            ->where('year', Config::get('applicationConfig.operation_year'))
            ->update(array('status' => 1));

          if(DB::table('audition')->where('national_id', $national_id)->where('club_code', Session::get('club_code'))->where('year', Config::get('applicationConfig.operation_year'))->count() == 1){
            return 'confirm';
          }else{
            return 'error';
          }
        break;
        case 'dismiss':
          DB::table('audition')
            ->where('national_id', $national_id)
            ->where('club_code', Session::get('club_code'))
            ->where('year', Config::get('applicationConfig.operation_year'))
            ->update(array('status' => -1));

          if(DB::table('audition')->where('national_id', $national_id)->where('club_code', Session::get('club_code'))->where('year', Config::get('applicationConfig.operation_year'))->count() == 1){
            return 'dismiss';
          }else{
            return 'error';
          }
        break;
        default:
          return false;
        break;
      }
    }
  }

  /**
   * Cancel audition
   *
   * @return string on success
   * @return false on failure
   */
  public function auditionCancel(){
    $national_id_encrypted  = Input::get('national_id');
    $action                 = Input::get('action');

    $national_id = Crypt::decrypt($national_id_encrypted);

    switch($action){
      case 'cancel':
        //Get user out of our club
        DB::table('audition')
          ->where('national_id', $national_id)
          ->where('club_code', Session::get('club_code'))
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->update(array('status' => 0));

        return 'cancel';
      break;
      default:
        return false;
      break;
    }
  }

  /**
   * Get all data of all student in club
   *
   * @param bool whether or not you want national_id in return array to be encrypt
   * @return array array of standard object
   */
  public function getAllStudentList($encryptNationalID = false){
    $data1 = DB::table('confirmation')
              ->join('user_year', function($join){
                $join->on('confirmation.national_id', '=', 'user_year.national_id')
                     ->on('confirmation.year', '=', 'user_year.year')
                     ->on('confirmation.club_code', '=', 'user_year.club_code');
              })
              ->join('user', 'confirmation.national_id', '=', 'user.national_id')
              ->where('user_year.year', Config::get('applicationConfig.operation_year'))
              ->where('user_year.club_code', Session::get('club_code'))
              ->orderBy('user_year.class', 'asc')
              ->orderBy('user_year.room', 'asc')
              ->orderBy('user_year.number', 'asc')
              ->get();

    if(Operation::isClubAudition(Session::get('club_code'))){
      $data2 = DB::table('audition')
                ->join('user_year', function($join){
                  $join->on('audition.national_id', '=', 'user_year.national_id')
                       ->on('audition.year', '=', 'user_year.year');
                })
                ->join('user', 'audition.national_id', '=', 'user.national_id')
                ->where('audition.year', Config::get('applicationConfig.operation_year'))
                ->where('audition.club_code', Session::get('club_code'))
                ->where('audition.status', 2)
                ->orderBy('user_year.class', 'asc')
                ->orderBy('user_year.room', 'asc')
                ->orderBy('user_year.number', 'asc')
                ->get();
    }else{
      $data2 = DB::table('registration')
                ->join('user_year', function($join){
                  $join->on('registration.national_id', '=', 'user_year.national_id')
                       ->on('registration.year', '=', 'user_year.year')
                       ->on('registration.club_code', '=', 'user_year.club_code');
                })
                ->join('user', 'registration.national_id', '=', 'user.national_id')
                ->where('user_year.year', Config::get('applicationConfig.operation_year'))
                ->where('user_year.club_code', Session::get('club_code'))
                ->orderBy('user_year.class', 'asc')
                ->orderBy('user_year.room', 'asc')
                ->orderBy('user_year.number', 'asc')
                ->get();
    }

    DB::beginTransaction();
      try{
        $data = array_merge($data1, $data2); //Merge the two array
        $table_name = 'a'.mb_substr(Session::get('club_code'), 1); // Remove ก from club code to prevent problem
        DB::statement('
          CREATE TEMPORARY TABLE IF NOT EXISTS `'. $table_name .'` LIKE `club_name_list_template`
        '); //Create the table for sorting
        if(DB::table($table_name)->count() == 0){
          for($i=0; $i<count($data); $i++){
            DB::table($table_name)->insert(array(
              'student_id' => $data[$i]->student_id,
              'national_id' => $data[$i]->national_id,
              'title' => $data[$i]->title,
              'fname' => $data[$i]->fname,
              'lname' => $data[$i]->lname,
              'class' => $data[$i]->class,
              'room' => $data[$i]->room,
              'number' => $data[$i]->number,
              'club_code' => $data[$i]->club_code,
              'year' => $data[$i]->year
            ));
          }
        }else{
          throw new DataException("Table Not Empty");
        }
      }catch(DataException $e){
        DB::rollBack();
        echo 'Caught DataException: ',  $e->getMessage(), "\n";
      }catch(Exception $e){
        DB::rollBack();
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    DB::commit();

    $return = DB::table($table_name)
                ->where('year', Config::get('applicationConfig.operation_year'))
                ->where('club_code', Session::get('club_code'))
                ->orderBy('class', 'asc')
                ->orderBy('room', 'asc')
                ->orderBy('number', 'asc')
                ->get();

    DB::statement('DROP TEMPORARY TABLE IF EXISTS `'. $table_name.'`');

    if($encryptNationalID){
      $data = self::encryptNationalID($data);
    }

    return $return;
  }

  /**
   * Get club's president fullname
   *
   * @param   string specify what data type to return value: array (default), string
   * @return  string, array
   */
  public function getPresidentName($type = 'array'){
    $data = DB::table('club')->where('club_code', Session::get('club_code'))->first();
    if($type == 'array'){
      return array($data->president_title, $data->president_fname, $data->president_lname);
    }else if($type == 'string'){
      return $data->president_title.' '.$data->president_fname.' '.$data->president_lname;
    }
  }

  /**
   * Get club's adviser fullname
   *
   * @param   string specify what data type to return value: array (default), string
   * @return  string, array
   */
  public function getAdviserName($type = 'array'){
    $data = DB::table('club')->where('club_code', Session::get('club_code'))->first();
    if($type == 'array'){
      return array($data->adviser_title, $data->adviser_fname, $data->adviser_lname);
    }else if($type == 'string'){
      return $data->adviser_title.' '.$data->adviser_fname.' '.$data->adviser_lname;
    }
  }

  /**
   * Update president and adviser fullname in table club
   *
   * @param string
   * @return true
   */
  public function nameSetUp($president_title, $president_fname, $president_lname, $adviser_title, $adviser_fname, $adviser_lname){
    DB::table('club')
      ->where('club_code', Session::get('club_code'))
      ->update(array(
        'president_title' => $president_title,
        'president_fname' => $president_fname,
        'president_lname' => $president_lname,
        'adviser_title' => $adviser_title,
        'adviser_fname' => $adviser_fname,
        'adviser_lname' => $adviser_lname
      ));
    return true;
  }

  /**
   * Get member that pass
   *
   * @param bool whether or not you want the output national_id to be encrypt
   * @return array
   */
  public function getMemberPass($encryptNationalID = false){
    $memberNotPass = $this->getMemberNotPass();

    for($i=0;$i<count($memberNotPass);$i++){
      $notPassNationalID[] = $memberNotPass[$i]->national_id;
    }

    if(isset($notPassNationalID)){
      $memberPass = DB::table('user_year')
                      ->join('user', 'user_year.national_id', '=', 'user.national_id')
                      ->where('user_year.year', Config::get('applicationConfig.operation_year'))
                      ->where('user_year.club_code', Session::get('club_code'))
                      ->whereNotIn('user_year.national_id', $notPassNationalID)
                      ->get();
    }else{
      $memberPass = DB::table('user_year')
                      ->join('user', 'user_year.national_id', '=', 'user.national_id')
                      ->where('user_year.year', Config::get('applicationConfig.operation_year'))
                      ->where('user_year.club_code', Session::get('club_code'))
                      ->get();
    }

    if($encryptNationalID){
      $data = self::encryptNationalID($memberPass);
    }

    return $data;
  }

  /**
   * Get member that does not pass
   *
   * @param bool whether or not you want the output national_id to be encrypt
   * @return array
   */
  public function getMemberNotPass($encryptNationalID = false){
    $data = DB::table('not_pass_user')
              ->join('user', 'not_pass_user.national_id', '=', 'user.national_id')
              ->join('user_year', function($join){
                $join->on('not_pass_user.national_id', '=', 'user_year.national_id')
                     ->on('not_pass_user.year', '=', 'user_year.year')
                     ->on('not_pass_user.club_code', '=', 'user_year.club_code');
              })
              ->where('not_pass_user.year', Config::get('applicationConfig.operation_year'))
              ->where('not_pass_user.club_code', Session::get('club_code'))
              ->get();

    if($encryptNationalID){
      $data = self::encryptNationalID($data);
    }

    return $data;
  }

  /**
   * Create FM3301
   *
   * @return  string path to generated FM3301 file
   */
  public function createFM3301(){
    $studentData = $this->getAllStudentList();
    $clubData = DB::table('club')->where('club_code', Session::get('club_code'))->first();
    $adviserCount = DB::table('teacher_year')
                      ->where('club_code', Session::get('club_code'))
                      ->where('year', Config::get('applicationConfig.operation_year'))
                      ->count();
    $criterionCount = $adviserCount*30;

    $fileName = '[FM 33-01] '.substr(Session::get('club_code'), -2).'_'.Session::get('fullname');
    if(file_exists(public_path('FMOutput/'.$fileName.'.docx'))){
      unlink(public_path('FMOutput/'.$fileName.'.docx'));
    }

    $templateProcessor = new TemplateProcessor(base_path('resources/FMtemplate/FM3301.docx'));

    $templateProcessor->setValue('clubName',            htmlspecialchars($clubData->club_name));
    $templateProcessor->setValue('clubCode',            htmlspecialchars($clubData->club_code));
    $templateProcessor->setValue('adviserCount',        htmlspecialchars($adviserCount));
    $templateProcessor->setValue('criterionCount',      htmlspecialchars($criterionCount));

    $class4StudentCount = 0;
    $class5StudentCount = 0;
    $class6StudentCount = 0;
    $studentCount = count($studentData);

    for($i=0;$i<$studentCount;$i++){
      switch($studentData[$i]->class){
        case 4:
          $class4StudentCount += 1;
        break;
        case 5:
          $class5StudentCount += 1;
        break;
        case 6:
          $class6StudentCount += 1;
        break;
        default:
          throw new DataException("Class ".$studentData[$i]->class." does not exists.");
        break;
      }
    }

    $lessThanCriterion = 0;
    $moreThanCriterion = 0;

    if($studentCount > $adviserCount*30){
      $moreThanCriterion = $studentCount - $adviserCount*30;
    }else if($studentCount < $adviserCount*30){
      $lessThanCriterion = $adviserCount*30 - $studentCount;
    }

    $templateProcessor->setValue('totalStudentCount',     htmlspecialchars($studentCount));
    $templateProcessor->setValue('class4StudentCount',    htmlspecialchars($class4StudentCount));
    $templateProcessor->setValue('class5StudentCount',    htmlspecialchars($class5StudentCount));
    $templateProcessor->setValue('class6StudentCount',    htmlspecialchars($class6StudentCount));
    $templateProcessor->setValue('lessThanCriterion',     htmlspecialchars($lessThanCriterion));
    $templateProcessor->setValue('moreThanCriterion',     htmlspecialchars($moreThanCriterion));

    $templateProcessor->cloneRow('count', $studentCount);

    for($j=0;$j<$studentCount;$j++){
      $k = $j+1;
      $templateProcessor->setValue('count#'.$k, $k);

      $templateProcessor->setValue('tfname#'.$k,          htmlspecialchars($studentData[$j]->title.' '.$studentData[$j]->fname));
      $templateProcessor->setValue('lname#'.$k,           htmlspecialchars($studentData[$j]->lname));

      $templateProcessor->setValue('class#'.$k,           htmlspecialchars('ม.'.$studentData[$j]->class));
      $templateProcessor->setValue('room#'.$k,            htmlspecialchars($studentData[$j]->room));
    }

    $templateProcessor->setValue('operation_year',        htmlspecialchars(Config::get('applicationConfig.operation_year')));

    $presidentName = $this->getPresidentName('string');
    $adviserName = $this->getAdviserName('string');
    $templateProcessor->setValue('presidentName',         htmlspecialchars($presidentName));
    $templateProcessor->setValue('adviserName',           htmlspecialchars($adviserName));

    $templateProcessor->saveAs(public_path('FMOutput/'.$fileName.'.docx'));
    return public_path('FMOutput/'.$fileName.'.docx');
  }

  /**
   * Create FM3304
   *
   * @param   int     semester that this FM3304 assigned to
   * @return  string  path to generated FM3304 file
   */
  public function createFM3304($semester){
    $studentData = $this->getAllStudentList();
    $clubData = DB::table('club')->where('club_code', Session::get('club_code'))->first();

    $fileName = '[FM 33-04] '.substr(Session::get('club_code'), -2).'_'.Session::get('fullname');
    if(file_exists(public_path('FMOutput/'.$fileName.'.docx'))){
      unlink(public_path('FMOutput/'.$fileName.'.docx'));
    }

    $templateProcessor = new TemplateProcessor(base_path('resources/FMtemplate/FM3304.docx'));

    $templateProcessor->setValue('clubName',             htmlspecialchars($clubData->club_name));
    $templateProcessor->setValue('clubCode',             htmlspecialchars($clubData->club_code));
    $templateProcessor->setValue('semester',             htmlspecialchars($semester));
    $templateProcessor->setValue('operation_year',       htmlspecialchars(Config::get('applicationConfig.operation_year')));

    $studentCount = count($studentData);
    $templateProcessor->cloneRow('count', $studentCount);

    for($j=0;$j<$studentCount;$j++){
      $k = $j+1;
      $templateProcessor->setValue('count#'.$k, $k);

      $templateProcessor->setValue('tfname#'.$k,          htmlspecialchars($studentData[$j]->title.' '.$studentData[$j]->fname));
      $templateProcessor->setValue('lname#'.$k,           htmlspecialchars($studentData[$j]->lname));

      $templateProcessor->setValue('class-room#'.$k,      htmlspecialchars($studentData[$j]->class.'/'.$studentData[$j]->room));
    }

    $adviserName = $this->getAdviserName('string');
    $templateProcessor->setValue('adviserName',           htmlspecialchars($adviserName));

    $templateProcessor->saveAs(public_path('FMOutput/'.$fileName.'.docx'));
    return public_path('FMOutput/'.$fileName.'.docx');
  }

  /**
   * Add user to not_pass_user
   *
   * @param string encrypted national_id
   * @param int    semester
   * @return true on success
   * @return string error message upon failure
   */
  public function addUserToNotPass($national_id_encrypted, $semester){
    $national_id = Crypt::decrypt($national_id_encrypted);
    if(Operation::isUserInClub($national_id, Session::get('club_code'))){
      if(DB::table('not_pass_user')->where('national_id', $national_id)->where('year', Config::get('applicationConfig.operation_year'))->exists()){
        return "มีข้อมูลนักเรียนคนนี้ในรายชื่อไม่ผ่านชมรมแล้ว ไม่สามารถเพิ่มข้อมูลซ้ำได้";
      }else{
        DB::table('not_pass_user')->insert(array(
          'national_id' => $national_id,
          'semester' => $semester,
          'year' => Config::get('applicationConfig.operation_year'),
          'club_code' => Session::get('club_code')
        ));

        return true;
      }
    }else{
      return "นักเรียนคนนี้ไม่มีในรายชื่อสมาชิกชมรม";
    }
  }

  /**
   * Remove user from not_pass_user
   *
   * @param string encrypted national_id
   * @param int    semester
   * @return true on success
   * @return string error message upon failure
   */
  public function removeUserFromNotPass($national_id_encrypted, $semester){
    $national_id = Crypt::decrypt($national_id_encrypted);
    if(Operation::isUserInClub($national_id, Session::get('club_code'))){
      if(!DB::table('not_pass_user')->where('national_id', $national_id)->where('year', Config::get('applicationConfig.operation_year'))->exists()){
        return "ไม่มีข้อมูลนักเรียนคนนี้ในรายชื่อไม่ผ่านชมรม ไม่สามารถลบได้";
      }else{
        DB::table('not_pass_user')
          ->where('national_id', $national_id)
          ->where('semester', $semester)
          ->where('year', Config::get('applicationConfig.operation_year'))
          ->delete();

        return true;
      }
    }else{
      return "นักเรียนคนนี้ไม่มีในรายชื่อสมาชิกชมรม";
    }
  }

  /**
   * Create FM3305
   *
   * @param   int     semester that this FM3305 assigned to
   * @return  string  path to generated FM3305 file
   */
  public function createFM3305($semester){
    $studentData = $this->getAllStudentList();
    $studentNotPass = $this->getMemberNotPass();
    $clubData = DB::table('club')->where('club_code', Session::get('club_code'))->first();

    $fileName = '[FM 33-05] '.substr(Session::get('club_code'), -2).'_'.Session::get('fullname');
    if(file_exists(public_path('FMOutput/'.$fileName.'.docx'))){
      unlink(public_path('FMOutput/'.$fileName.'.docx'));
    }

    $templateProcessor = new TemplateProcessor(base_path('resources/FMtemplate/FM3305.docx'));

    $templateProcessor->setValue('clubName',             htmlspecialchars($clubData->club_name));
    $templateProcessor->setValue('clubCode',             htmlspecialchars($clubData->club_code));
    $templateProcessor->setValue('semester',             htmlspecialchars($semester));
    $templateProcessor->setValue('operation_year',       htmlspecialchars(Config::get('applicationConfig.operation_year')));

    $studentCount = count($studentData);
    $studentNotPassCount = count($studentNotPass);

    $templateProcessor->setValue('total',                htmlspecialchars($studentCount));
    $templateProcessor->setValue('pass',                 htmlspecialchars($studentCount-count($studentNotPass)));
    $templateProcessor->setValue('fail',                 htmlspecialchars($studentNotPassCount));

    $templateProcessor->cloneRow('count', $studentNotPassCount);

    for($j=0;$j<$studentNotPassCount;$j++){
      $k = $j+1;
      $templateProcessor->setValue('count#'.$k, $k);

      $templateProcessor->setValue('tfname#'.$k,         htmlspecialchars($studentNotPass[$j]->title.' '.$studentNotPass[$j]->fname));
      $templateProcessor->setValue('lname#'.$k,          htmlspecialchars($studentNotPass[$j]->lname));

      $templateProcessor->setValue('class#'.$k,          htmlspecialchars($studentNotPass[$j]->class));
      $templateProcessor->setValue('room#'.$k,           htmlspecialchars($studentNotPass[$j]->room));
    }

    $adviserName = $this->getAdviserName('string');
    $templateProcessor->setValue('adviserName',          htmlspecialchars($adviserName));
    $templateProcessor->setValue('day',                  htmlspecialchars(date('j')));
    $month = array(
      1 => 'มกราคม',
      2 => 'กุมภาพันธ์',
      3 => 'มีนาคม',
      4 => 'เมษายน',
      5 => 'พฤษภาคม',
      6 => 'มิถุนายน',
      7 => 'กรกฎาคม',
      8 => 'สิงหาคม',
      9 => 'กันยายน',
      10 => 'ตุลาคม',
      11 => 'พฤศจิกายน',
      12 => 'ธันวาคม'
    );
    $templateProcessor->setValue('month',                htmlspecialchars($month[date('n')]));
    $templateProcessor->setValue('year',                 htmlspecialchars(date('Y')+543));

    $templateProcessor->saveAs(public_path('FMOutput/'.$fileName.'.docx'));
    return public_path('FMOutput/'.$fileName.'.docx');
  }
}
