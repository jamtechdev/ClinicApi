<?php
namespace App\Controllers;
require_once APPPATH .'/libraries/JWT.php';
use CodeIgniter\RestServer\RestController;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\StaffAttendance;
use App\Models\StaffLeaves;
use App\Models\TreatmentInstruction;
use App\Models\Official_papers;
use App\Models\EthicsPolicy;
use App\Models\Appointment;
use App\Models\DoctorAvailability;
use  \Firebase\JWT\JWT;
class Api extends ResourceController
{
    use ResponseTrait;
    private $key;
    function __construct()
    {
        /*parent::__construct();*/
        $this->model = new User();
        $this->model2 = new UserDetails();
    }

    public function validate_token()
    { 
        $this->key  = $this->getKey();
        $authHeader = $this->request->getHeader("Authorization");
        $authHeader = $authHeader->getValue();
        $token      = $authHeader;
        try {
            $decoded = JWT::decode($token, $this->key, array("HS256"));
            if ($decoded) {
                $response = [
                    'status'   => 200,
                    'error'    => false,
                    'messages' => 'User details',
                    'data'     => [
                        'profile' => $decoded
                    ]
                ];
            } 
        } catch (Exception $ex) {
            $response = [
                'status'   => 401,
                'error'    => true,
                'messages' => 'Access denied',
                'data'     => []
            ];
        }
         return $response;
    }

    public function index()
    {
        $model = new User();
        $data = array('status' => 'ok', 'msg' => 'Successfully');
        $data['res'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    public function register()
    {
        $rules = [
            "email"         => "required|valid_email|is_unique[user.email]",
            "user_password" => "required",
            "user_type"     => "required",
            "first_name"    => "required",
            "last_name"     => "required",
            "mobile_no"     => "regex_match[/^[0-9]{10}$/]",
        ];
        $messages = [
            "email" => [
                "required"    => "Email required",
                "valid_email" => "Email address is not in format",
                "is_unique"   => "This email address is already exists!!",
            ],
            "user_password" => [
                "required" => "Password is required !"
            ],
            "user_type" => [
                "required" => "User type is required !"
            ],
            "first_name" => [
                "required" => "First name is required !"
            ],
            "last_name" => [
                "required" => "Last name type is required !"
            ],
            "regex_match" => [
                "required" => "Mobile number is invalid !"
            ],
        ];
        if (!$this->validate($rules, $messages)) {

            $response = [
                'status'  => 500,
                'error'   => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $userModel = new User();
            $data = [
                "unique_id"       => md5(uniqid(time())),
                "email"           => $this->request->getVar("email"),
                "user_password"   => password_hash($this->request->getVar("user_password"), PASSWORD_DEFAULT),
                "user_type"       => $this->request->getVar("user_type"),
                "status"          => ($this->request->getVar("status")) ? $this->request->getVar("status") : 1,
           ];
            $register     = $userModel->insert($data);
            if ($register) {
                $UserDetails  = new UserDetails();
                $file         = $this->request->getFile('license_file');
                $license      = $file->getRandomName();
                $uploadPath   = $file->move(WRITEPATH.'/uploads',$license);
                $user_details = [
                    "user_id"         => $register,
                    "first_name"      => $this->request->getVar("first_name"),
                    "last_name"       => $this->request->getVar("last_name"),
                    "address"         => $this->request->getVar("address"),
                    "mobile_no"       => $this->request->getVar("mobile_no"),
                    "blood_group"     => $this->request->getVar("blood_group"),
                    "dob"             => $this->request->getVar("dob"),
                    "age"             => $this->request->getVar("age"),
                    "gender"          => $this->request->getVar("gender"),
                    "marital_status"  => $this->request->getVar("marital_status"),
                    "license_type"    => $this->request->getVar("license_type"),
                    "license_file"    => $license,
                    "license_number"  => $this->request->getVar("license_number"),
                    "license_exp"     => $this->request->getVar("license_exp")
                ];
                $registrationDetails = $UserDetails->insert($user_details); 
                if ($registrationDetails) {
                    $response = [
                        'status'       => 200,
                        "error"        => false,
                        'messages'     => 'Successfully, user has been registered!',
                        'user_details' => $user_details
                    ];
                } else {
                    $response = [
                        'status'       => 500,
                        "error"        => true,
                        'messages'     => 'Failed to create user',
                    ];
                }
            } else {
                $response = [
                    'status'   => 500,
                    "error"    => true,
                    'messages' => 'Failed to create users',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    private function getKey()
    {
        return "my_application_secret";
    }

    public function login()
    {
        $rules = [
            "email" => "required|valid_email",
            "user_password" => "required",
        ];
        $messages = [
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "user_password" => [
                "required" => "Password is required"
            ],
        ];
        if (!$this->validate($rules, $messages)) {

            $response = [
                'status'  => 500,
                'error'   => true,
                'message' => $this->validator->getErrors(),
            ];
            return $this->respondCreated($response);
        } else {
            $userdata = $this->model->where("email", $this->request->getVar("email"))->first();
            if (!empty($userdata)) {
                if (password_verify($this->request->getVar("user_password"), $userdata['user_password'])) {
                    $key = $this->getKey();
                    $iat = time(); // current timestamp value
                    $nbf = $iat + 10;
                    $exp = $iat + 3600;
                    $payload = array(
                        "iss"  => "The_claim",
                        "aud"  => "The_Aud",
                        "iat"  => $iat, // issued at
                        "nbf"  => $nbf, //not before in seconds
                        "exp"  => $exp, // expire time in seconds
                        "data" => $userdata,
                    );
                    $token = JWT::encode($payload, $key);
                    $response = [
                        'status'   => 200,
                        'error'    => false,
                        'messages' => 'User logged In successfully',
                        'data'     => [
                            'token' => $token,
                        ]
                    ];
                    return $this->respondCreated($response);
                } else {
                    $response = [
                        'status'   => 500,
                        'error'    => true,
                        'messages' => 'Incorrect details',
                        'data'     => []
                    ];
                    return $this->respondCreated($response);
                }
            } else {
                $response = [
                    'status'   => 500,
                    'error'    => true,
                    'messages' => 'User not found',
                    'data'     => []
                ];
                return $this->respondCreated($response);
            }
        }
    }

    public function get_profile()
    {
        $user = $this->validate_token();
        if($user){
           return $this->respondCreated($user);
         } else {
            $response = [
                    'status'   => 500,
                    'error'    => true,
                    'messages' => 'User not found',
                    'data'     => []
                ];
            return $this->respondCreated($response);
        }
    }

    public function delete_user()
     {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status'   => 400,
                "error"    => true,
                'messages' => 'Invalid User',
                'data'     => []
            ];
            return $this->respondCreated($response);
        }
        $get_user = $user['data']['profile']->data->user_type;
        $userModel = new User();
        $user_id = $this->request->getVar("user_id");
        $UserDetails = new UserDetails();
        $db_data = $UserDetails->where('user_id', $user_id)->first();
        if($get_user == 'admin'){                 
                    $userModel->delete($user_id);
                    $UserDetails->delete($db_data['id']);
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => [
                            'success' => 'Data Deleted'
                        ]
                    ];
                    return $this->respondDeleted($response);
                }else{
                    return $this->failNotFound('You are not authorized to perform this action!');
                }
    }

    public function update_user()
    {   
        $user = $this->validate_token();

        if(!$user){
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Invalid User',
                'data'     => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "first_name"    => "required",
            "last_name"     => "required",
        ];
        $messages = [
            "first_name" => [
                "required" => "First name is required !"
            ],
            "last_name" => [
                "required" => "Last name is required !"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'  => 500,
                'error'   => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $user_id        = $this->request->getVar("user_id");
            $file           = $this->request->getFile('license_file');
            $license        = $file->getRandomName();
            $uploadPath     = $file->move(WRITEPATH.'/uploads',$license);
            $UserDetails    = new UserDetails();
            $UserDetailsRow = $UserDetails->where('user_id', $user_id)->first();
            if ($UserDetailsRow) {
                $user_details = [
                    "user_id"         => $user_id,
                    "first_name"      => $this->request->getVar("first_name"),
                    "last_name"       => $this->request->getVar("last_name"),
                    "address"         => $this->request->getVar("address"),
                    "mobile_no"       => $this->request->getVar("mobile_no"),
                    "blood_group"     => $this->request->getVar("blood_group"),
                    "dob"             => $this->request->getVar("dob"),
                    "age"             => $this->request->getVar("age"),
                    "gender"          => $this->request->getVar("gender"),
                    "marital_status"  => $this->request->getVar("marital_status"),
                    "license_type"    => $this->request->getVar("license_type"),
                    "license_file"    => $license ,
                    "license_number"  => $this->request->getVar("license_number"),
                    "license_exp"     => $this->request->getVar("license_exp")
                ];
                $registrationDetails = $UserDetails->update($UserDetailsRow['id'], $user_details);
                if ($registrationDetails) {
                    $response = [
                        'status'       => 200,
                        "error"        => false,
                        'messages'     => 'Successfully, User has been updated!',
                        'user_details' =>$user_details
                    ];
                } else {
                    $response = [
                        'status'   => 500,
                        "error"    => true,
                        'messages' => 'Failed to update user!',
                    ];
                }
            } else {
                $response = [
                    'status'   => 500,
                    "error"    => true,
                    'messages' => 'Failed to update users!',
                ];
            }
        }
        return $this->respondCreated($response);
    }
    
    public function update_status()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Invalid User',
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "status"    => "required"
        ];
        $messages = [
            "status" => [
                "required" => "status is required !"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        }  else  {
            $user_id = $this->request->getVar("user_id");
            $UserDetails = new User();
            $db_data = $UserDetails->where('id', $user_id)->first();
            
            if ($db_data) {
                $user_details = [
                    "status"      => $this->request->getVar("status")
                ];
                $updateStatus = $UserDetails->update($db_data, $user_details);
                if ($updateStatus) {
                    $response = [
                        'status' => 200,
                        "error" => false,
                        'messages' => 'Successfully, update status!',
                    ];
                } else {
                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Failed to update status!',
                    ];
                }
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to update status!',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function staff_attendance()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "action_type"     => "required",
        ];
        $messages = [
            "action_type" => [
                "required" => "Action type is required !",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
             $user_id = $user['data']['profile']->data->id;
             $action_type = $this->request->getVar("action_type");
              if($user_id) {
                $StaffAttendance = new StaffAttendance();
                $alreadyLoginDetail = $StaffAttendance->where('user_id', $user_id)
                                           ->where('action_type', $action_type)
                                           ->where("DATE_FORMAT(at_time,'%Y-%m-%d')", date("Y-m-d"))
                                           ->first();
                    if($alreadyLoginDetail){
                        $response = [
                            'status' => 200,
                            "error" => false,
                            'messages' => 'Already '. $action_type,
                            'data' => []
                        ];
                        return $this->respondCreated($response);
                    } 
                $user_details = [
                    "user_id"      => $user_id,
                    "action_type"  => $this->request->getVar("action_type"),
                    "at_time"      => date("Y-m-d H:i:s")
                ];
                $AttendanceDetails = $StaffAttendance->insert($user_details);
                if ($AttendanceDetails) {
                    $response = [
                        'status' => 200,
                        "error" => false,
                        'messages' => 'Successfully, '. $action_type .' attendance',
                    ];
                } else {
                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Failed to create attendance',
                     ];
                }
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to create attendance',
                    'data' => []
                ];
            }
        }
        return $this->respondCreated($response);
    }  

    public function findStaffAttendance()
    {
      $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "from_date"     => "required",
            "to_date"       => "required",
        ];
        $messages = [
            "from_date" => [
                "required" => "Select start date !",
            ],
            "to_date" => [
                "required" => "Select end date !",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
             $user_id = $user['data']['profile']->data->id;
             $from_date = $this->request->getVar("from_date");
             $to_date = $this->request->getVar("to_date");
             $dateArray = $this->createDateRangeArray($from_date, $to_date);
             $loginDetail = array();
             $StaffAttendance = new StaffAttendance();
             foreach ($dateArray as $dateTime) {
                 $tmp = array();
                 $loginRow = $StaffAttendance->where('user_id', $user_id)
                                             ->where("action_type", "login")
                                             ->where("DATE_FORMAT(at_time,'%Y-%m-%d')", $dateTime)
                                             ->first();
                $logoutRow = $StaffAttendance->where('user_id', $user_id)
                                             ->where("action_type", "logout")
                                             ->where("DATE_FORMAT(at_time,'%Y-%m-%d')", $dateTime)
                                             ->first();                                 
                 $tmp['date']   = $dateTime;
                 $tmp['login']  = $loginRow['at_time'] ? date('H:i', strtotime($loginRow['at_time'])) : 'N/A';
                 $tmp['logout'] = $logoutRow['at_time'] ? date('H:i', strtotime($logoutRow['at_time'])) : 'N/A';
                 $loginDetail[] = $tmp;
             }
              if($loginDetail){
                    $response = [
                        'status' => 200,
                        "error" => false,
                        'messages' => 'All staff attendance ',
                        'data' => $loginDetail
                    ];
                    return $this->respondCreated($response);
                } else {
                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Failed to find attendance',
                    ];
                }
        }
        return $this->respondCreated($response);
    }

    function createDateRangeArray($strDateFrom,$strDateTo)
    {
        $aryRange = [];
        $iDateFrom = mktime(0, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(0, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }

    public function staffLeave()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "subject"     => "required",
            "message"     => "required",
        ];
        $messages = [
            "subject" => [
                "required" => "Subject is required !",
            ],
            "message" => [
                "required" => "Message is required !",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $user_id = $this->request->getFile('user_id');
            $file = $this->request->getFile('leave_img');
            $newName = $file->getRandomName();
            $uploadPath = $file->move(WRITEPATH.'/uploads',$newName);
            $StaffLeaves = new StaffLeaves();
            $data = [
                "user_id"       => $user_id,
                "subject"       => $this->request->getVar("subject"),
                "message"       => $this->request->getVar("message"),
                "leave_img"     => $newName,
                "status"        => 0 
            ];
             $leaves = $StaffLeaves->insert($data);
            if($leaves){
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Staff leave applied',
                    'data' => $data
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to apply leave',
                ];
            }
        }
        return $this->respondCreated($response);
    }    


    public function staffLeaveStatus()
    {
       $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "status"     => "required",
        ];
        $messages = [
            "status" => [
                "required" => "Staff leave status ",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
         $user_id = $user['data']['profile']->data->id;
         $StaffLeaves = new StaffLeaves();
         $StaffLeavesId = $this->request->getVar("id");
           $data = [
                "approvedBy"       => $user_id,
                "status"           => $this->request->getVar("status")
           ];
             $leaves = $StaffLeaves->update($StaffLeavesId, $data);
            if($leaves){
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Successfully, Change leave status !',
                    'data' => $leaves
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to change leave status !',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function patient_list()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => $user
            ];
            return $this->respondCreated($response);
        } 
            $userdata = new User();
            $unique_id = $this->request->getVar("unique_id");
            $data = $userdata->getPatientList($unique_id);
            if($data){
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Patient all details found!',
                    'patientDetails' => $data,
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to find patient details!',
                ];
            }          
        return $this->respondCreated($response);
    }

    public function treatmentInstruction()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        } 
        $rules = [
            "before_treatment"  => "required",
            "after_treatment"   => "required",
        ];
        $messages = [
            "before_treatment" => [
                "required"     => "Instruction for patient, before treatment !"
            ],
            "after_treatment" => [
                "required"    => "Instruction for patient, after treatment !"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'   => 500,
                'error'    => true,
                'message'  => $this->validator->getErrors(),
            ];
        } else {
                $user_id = $user['data']['profile']->data->id;
                $treatmentInstruction = new TreatmentInstruction();                
                $user_details = [
                    "user_id"               => $user_id,
                    "before_treatment"      => $this->request->getVar("before_treatment"),
                    "after_treatment"       => $this->request->getVar("after_treatment")
                ];
                $treatmentDetails = $treatmentInstruction->insert($user_details); 
                if ($treatmentDetails) {
                    $response = [
                        'status'       => 200,
                        "error"        => false,
                        'messages'     => 'Successfully, send treatment instruction!',
                        'user_details' => []
                    ];
                } else {
                    $response = [
                        'status'       => 500,
                        "error"        => true,
                        'messages'     => 'Failed to send treatment instruction!',
                        'user_details' => []
                    ];
                }
        }
        return $this->respondCreated($response);
    }

    public function deleteTreatmentInstruction()
     {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $TreatmentModel = new TreatmentInstruction();
        $delIns = $this->request->getVar("id");
        $TreatmentModel->delete($delIns);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Treatment Instruction, Deleted'
                ]
            ];
            return $this->respondDeleted($response);
    }

    public function editTreatmentInstruction()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        } 
        $rules = [
            "before_treatment"  => "required",
            "after_treatment"   => "required",
        ];
        $messages = [
            "before_treatment" => [
                "required"     => "Instruction for patient, before treatment !"
            ],
            "after_treatment" => [
                "required"    => "Instruction for patient, after treatment !"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'   => 500,
                'error'    => true,
                'message'  => $this->validator->getErrors(),
            ];
        } else {
            $user_id = $user['data']['profile']->data->id;
            $treatmentInstruction = new TreatmentInstruction(); 
            $db_data = $this->request->getVar("id");
            $UpdateTreatmentDetails = [
                "user_id"               => $user_id,
                "before_treatment"      => $this->request->getVar("before_treatment"),
                "after_treatment"       => $this->request->getVar("after_treatment")
            ];

            $treatmentDetails = $treatmentInstruction->update($db_data, $UpdateTreatmentDetails);
            if ($treatmentDetails) {
                $response = [
                    'status'       => 200,
                    "error"        => false,
                    'messages'     => 'Successfully, update treatment instruction!',
                    'UpdateTreatmentDetails' => $UpdateTreatmentDetails
                ];
            } else {
                $response = [
                    'status'       => 500,
                    "error"        => true,
                    'messages'     => 'Failed to update treatment instruction!',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function ethicsPolicy()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Invalid User',
                'data'     => []
            ];
            return $this->respondCreated($response);
        } 
        $rules = [
            "ethicsPolicy"     => "required",
        ];
        $messages = [
            "ethicsPolicy"     => [
                "required"     => "Professional ethics policy rules required!"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'   => 500,
                'error'    => true,
                'message'  => $this->validator->getErrors(),
            ];
        } else {
            $EthicsPolicy = new EthicsPolicy();   
            $data = $EthicsPolicy->first();
            if(!$data){          
                $PolicyRules = [
                    "ethicsPolicy"          => $this->request->getVar("ethicsPolicy")
                ];
                $EthicsPolicyRules = $EthicsPolicy->insert($PolicyRules); 
                if ($EthicsPolicyRules) {
                    $response = [
                        'status'       => 200,
                        "error"        => false,
                        'messages'     => 'Successfully, inserted Ethics Policy Rules!',
                        'insertRule' => $PolicyRules
                    ];
                } else {
                    $response = [
                        'status'       => 500,
                        "error"        => true,
                        'messages'     => 'Failed to add EthicsPolicyRules!',
                    ];
                }
            } else {
                    $PolicyRules = [
                        "ethicsPolicy"     => $this->request->getVar("ethicsPolicy"),
                    ];
                    $EthicsPolicyDetails = $EthicsPolicy->update($data['id'], $PolicyRules);
                    if ($EthicsPolicyDetails) {
                    $response = [
                        'status'       => 200,
                        "error"        => false,
                        'messages'     => 'Successfully, updated Ethics Policy Rules!',
                        'insertRule' => $PolicyRules
                    ];
                } else {
                    $response = [
                        'status'       => 500,
                        "error"        => true,
                        'messages'     => 'Failed to update EthicsPolicyRules!',
                    ];
                }
            }
        }
        return $this->respondCreated($response);
    }

    public function deleteEthicsPolicy()
     {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $EthicsPolicy = new EthicsPolicy();
        $deletePolicy = $this->request->getVar("id");
        $EthicsPolicy->delete($deletePolicy);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Policy of professional ethic, Deleted'
            ]
        ];
        return $this->respondDeleted($response);
    }

    public function clinic_papers()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Invalid User',
                'data'     => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "paper_notes"       => "required",
        ];
        $messages = [
            "paper_notes"  => [
                "required" => "Write official clinic papers of notes!",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'  => 500,
                'error'   => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $user_id = $user['data']['profile']->data->id;

            $clinic_files       = $this->request->getFile('clinic_files');
            $clinic  = $clinic_files->getRandomName();
            $clinic_files->move(WRITEPATH.'/uploads',$clinic);

            $Official_papers = new Official_papers();
            $data = [
                "user_id"              => $user_id,
                "title"                => $this->request->getVar("title"),
                "paper_notes"          => $this->request->getVar("paper_notes"),
                "clinic_files"         => $clinic,
            ];
             $document = $Official_papers->insert($data);
            if($document){
                $response = [
                    'status'   => 200,
                    "error"    => false,
                    'messages' => 'Official clinic papers of notes inserted!',
                    'data'     => $data
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status'   => 500,
                    "error"    => true,
                    'messages' => 'Failed to insert official clinic papers of notes',
                ];
            }         
        }
        return $this->respondCreated($response);
    }    

    public function editClinic_papers()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
            ];
            return $this->respondCreated($response);
        } 
        $rules = [
            "paper_notes"       => "required",
        ];
        $messages = [
            "paper_notes"  => [
                "required" => "Write official clinic papers of notes!",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status'   => 500,
                'error'    => true,
                'message'  => $this->validator->getErrors(),
            ];
        } else {
            $user_id      = $user['data']['profile']->data->id;
            $clinic_files = $this->request->getFile('clinic_files');
            $clinic       = $clinic_files->getRandomName();
            $clinic_files->move(WRITEPATH.'/uploads',$clinic);

            $db_data = $this->request->getVar("id");
            $Official_papers = new Official_papers();
            $data = [
                "user_id"              => $user_id,
                "title"                => $this->request->getVar("title"),
                "paper_notes"          => $this->request->getVar("paper_notes"),
                "clinic_files"         => $clinic,
            ];
            $documents = $Official_papers->update($db_data, $data);
            if ($documents) {
                $response = [
                    'status'       => 200,
                    "error"        => false,
                    'messages'     => 'Successfully, update treatment instruction!',
                    'data'         => $data
                ];
            } else {
                $response = [
                    'status'       => 500,
                    "error"        => true,
                    'messages'     => 'Failed to update treatment instruction!',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function deleteClinic_papers()
     {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $Official_papers = new Official_papers();
        $delIns = $this->request->getVar("id");
        $Official_papers->delete($delIns);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Successfully deleted'
            ]
        ];
        return $this->respondDeleted($response);
    }

    public function bookAppointment()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "specialist" => "required",
            "doctor"     => "required",
        ];
        $messages = [
            "specialist" => [
                "required" => "select specialist!",
            ],
            "doctor" => [
                "required" => "select doctor!"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $getPatientId = $user['data']['profile']->data->id;
            $aptDate = $this->request->getVar("appointment_date");
            $aptTime = $this->request->getVar("appointment_time");
            $Appointment = new Appointment();
            $db_aptDate = $Appointment->where('appointment_date', $aptDate)
                                      ->where('appointment_time', $aptTime)
                                      ->first();
            if(empty($db_aptDate)){
                $appointmentDetails = [
                    "patient_id"        => $getPatientId,
                    "specialist"        => $this->request->getVar("specialist"),
                    "doctor"            => $this->request->getVar("doctor"),
                    "message"           => $this->request->getVar("message"),
                    "appointment_date"  => $aptDate,
                    "appointment_time"  => $aptTime, 
                    "status"            => 0 
                ];
                $patientAppointment = $Appointment->insert($appointmentDetails); 
                if ($patientAppointment) {
                    $response = [
                        'status' => 200,
                        "error" => false,
                        'messages' => 'Successfully, booked your appointment!',
                        'appointmentDetails' => $appointmentDetails
                    ];
                } else {
                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Failed to book apppointment!',
                    ];
                }
            } else {
                    $response = [
                        'status' => 500,
                        "error" => true,
                        'messages' => 'Already booked apppointment to this date and time!',
                    ];
            }
        }
        return $this->respondCreated($response);
    }

    public function deleteAppointment()
     {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
            ];
            return $this->respondCreated($response);
        }
        $Appointment   = new Appointment();
        $appointmentId = $this->request->getVar("id");
        $Appointment->delete($appointmentId);
        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Deleted appointment.'
            ]
        ];
        return $this->respondDeleted($response);
    }

    public function updateAppointment()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "specialist" => "required",
            "doctor"     => "required",
        ];
        $messages = [
            "specialist" => [
                "required" => "select specialist!",
            ],
            "doctor" => [
                "required" => "select doctor!"
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
        $getPatientId = $user['data']['profile']->data->id;
        $aptDate = $this->request->getVar("appointment_date");
        $aptTime = $this->request->getVar("appointment_time");
        $Appointment = new Appointment();
        $db_aptDate = $Appointment->where('appointment_date', $aptDate)
                                  ->where('appointment_time', $aptTime)
                                  ->first();
         $appointmentId = $this->request->getVar("id");
         if(empty($db_aptDate)){
            $data = [
                "patient_id"        => $getPatientId,
                "specialist"        => $this->request->getVar("specialist"),
                "doctor"            => $this->request->getVar("doctor"),
                "message"           => $this->request->getVar("message"),
                "appointment_date"  => $aptDate,
                "appointment_time"  => $aptTime, 
                "status"            => 0
            ];

             $appointmentStatus = $Appointment->update($appointmentId, $data);
             if($appointmentStatus){
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Appointment status update !',
                    'data' => $appointmentStatus
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to update appointment!',
                ];
            } 
         } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Already booked apppointment to this date and time!',
                ];
         }
        }
        return $this->respondCreated($response);
    }

    public function changeAppointmentStatus()
    {
       $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => []
            ];
            return $this->respondCreated($response);
        }
        $rules = [
            "status"     => "required",
        ];
        $messages = [
            "status" => [
                "required" => "Patient appointment status",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
         $Appointment = new Appointment();
         $appointmentId = $this->request->getVar("id");
           $data = [
                "approvedBy"       => $this->request->getVar("approvedBy"),
                "status"           => $this->request->getVar("status")
           ];
            $appointmentStatus = $Appointment->update($appointmentId, $data);
            if($appointmentStatus){
                $response = [
                    'status' => 200,
                    "error" => false,
                    'messages' => 'Successfully change appointment status !',
                    'data' => $appointmentStatus
                ];
                return $this->respondCreated($response);
            } else {
                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'Failed to change appointment status !',
                ];
            }
        }
        return $this->respondCreated($response);
    }

    public function aptListForDoctor()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => $user
            ];
            return $this->respondCreated($response);
        } 
        $Appointment = new Appointment();
        $doctor_id = $this->request->getVar("doctor");
        $data = $Appointment->where('doctor', $doctor_id)->findAll();                  
        if($data){
            $response = [
                'status'     => 200,
                "error"      => false,
                'messages'   => 'All appointment list found!',
                'aptDetails' => $data,
            ];
            return $this->respondCreated($response);
        } else {
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Failed to find appointment list!',
            ];
        }          
        return $this->respondCreated($response);
    }

    public function aptListForPatient()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => $user
            ];
            return $this->respondCreated($response);
        } 
        $Appointment = new Appointment();
        $patient = $this->request->getVar("patient_id");
        $data = $Appointment->where('patient_id', $patient)->findAll();                  
        if($data){
            $response = [
                'status'   => 200,
                "error"    => false,
                'messages' => 'Patient all details found!',
                'appointmentDetails' => $data,
            ];
            return $this->respondCreated($response);
        } else {
            $response = [
                'status'   => 500,
                "error"    => true,
                'messages' => 'Failed to find patient details!',
            ];
        }          
        return $this->respondCreated($response);
    }

    public function doctorAvailability()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
                'data' => $user
            ];
            return $this->respondCreated($response);
        } 
        $rules = [
            "interval_time" => "required",
        ];
        $messages = [
            "interval_time" => [
                "required" => "Enter interval time!",
            ],
        ];
        if (!$this->validate($rules, $messages)) {
            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
            ];
        } else {
            $doctor_id = $this->request->getVar("doctor_id");
            $days = (array) $this->request->getVar('days');
            $interval_time = $this->request->getVar("interval_time");
            $newday = json_decode(json_encode($days), true);
            $week_days = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
            $DoctorAvailability = new DoctorAvailability();  

            foreach ($week_days as $day){
                $tmp = array();
                if(isset($newday)){
                    $tmp['doctor_id'] = $doctor_id;
                    $tmp['interval_time'] = $interval_time;
                    $tmp['day'] = $day;

                    $tmp['morning_start'] = $newday[$day]['morning_time']['start'];
                    $tmp['morning_end'] = $newday[$day]['morning_time']['end'];
                    $tmp['evening_start'] = $newday[$day]['evening_time']['start'];
                    $tmp['evening_end'] = $newday[$day]['evening_time']['end'];
                    
                    $alreadyAvailable = $DoctorAvailability->where('doctor_id', $doctor_id)
                                           ->where('day', $day)
                                           ->findAll();
                    if($alreadyAvailable){
                       $DoctorAvailability->delete($alreadyAvailable[0]['id']);
                    }
                   $DoctorAvailability->insert($tmp);
                }
                $response = [
                'status'   => 200,
                "error"    => false,
                'messages' => 'Succesfully added doctor availability !',
                ];
             }
        }
        return $this->respondCreated($response);
    }

    public function getDoctorAvailability()
    {
        $user = $this->validate_token();
        if(!$user){
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'Invalid User',
            ];
            return $this->respondCreated($response);
        } 
        $DoctorAvailability = new DoctorAvailability();
        $doctor_id = $this->request->getVar("doctor_id");
        $doctorAvailability = $DoctorAvailability->where('doctor_id', $doctor_id)->findAll();  
        
        $availability = array();    
        foreach ($doctorAvailability as $avl) {
            $tmp = array();
            $tmp['interval_time'] = $avl['interval_time'];
            $tmp[$avl['day']] = array(
                            'morning_start' => $avl['morning_start'],
                            'morning_end'   => $avl['morning_end'],
                            'evening_start' => $avl['evening_start'],
                            'evening_end'   => $avl['evening_end']
            );
            $availability[] = $tmp;
        }
        $response = [
            'status'   => 200,
            "error"    => false,
            'messages' => 'Get doctor availability !',
            'availability' => $availability
        ];
        return $this->respondCreated($response);
    }

}
