<?
swagger: "2.0"
info:
  description: "This is a sample server Petstore server. You can find out more about Swagger at [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/). For this sample, you can use the api key `special-key` to test the authorization filters."
  version: "1.0.0"
  title: "Doctor Clinic"

host: "localhost"
basePath: "/clinic_api/public/"
schemes:
- "http"
paths:
  /api/:
    get:
      tags:
      - "api"
      summary: "Find all users"
      description: "Returns a json array"
      produces:
      - "application/json"
      responses:
        "200":
          description: "successful operation"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      
  /api/register:
    post:
      tags:
      - "api"
      summary: "Registeration for all Users"
      operationId: "registerUser"
      consumes:
      - "multipart/form-data"
      produces:
      - "application/json"
      parameters:
      - name: "email"
        in: "formData"
        description: "The user email for registration"
        required: true
        type: "string"
      - name: "user_password"
        in: "formData"
        description: "The password for register in clear text"
        required: true
        type: "string"
      - name: "user_type"
        in: "formData"
        description: "Enter the user user_type"
        required: true
        type: "string"
      - name: "first_name"
        in: "formData"
        description: "The first name of user"
        required: true
        type: "string"
      - name: "last_name"
        in: "formData"
        description: "The last name of user"
        required: true
        type: "string"
      - name: "mobile_no"
        in: "formData"
        description: "Mobile number for registration"
        required: true
        type: "number"
      - name: "license_file"
        in: "formData"
        description: "License image to upload"
        required: false
        type: "file"
      - name: "address"
        in: "formData"
        description: "The password for register in clear text"
        required: false
        type: "string"
      - name: "blood_group"
        in: "formData"
        description: "User blood group "
        required: false
        type: "string"
      - name: "dob"
        in: "formData"
        description: "User date of birth "
        required: false
        type: "string"
      - name: "age"
        in: "formData"
        description: "Enter age of user"
        required: false
        type: "string"
      - name: "gender"
        in: "formData"
        description: "Gender of user "
        required: false
        type: "string"
      - name: "marital_status"
        in: "formData"
        description: "Marital status of user"
        required: false
        type: "string"
      - name: "license_type"
        in: "formData"
        description: "Type of user license"
        required: false
        type: "string"
      - name: "license_number"
        in: "formData"
        description: "License number of user"
        required: false
        type: "string"
      - name: "license_exp"
        in: "formData"
        description: "Expire date of user license"
        required: false
        type: "string"
      responses:
        "200":
          description: "successful operation"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "Order not found"
      
  /api/login:
    post:
      tags:
      - "api"
      summary: "Login user into the system"
      produces:
       - "application/xml"
       - "application/json"
      parameters:
      - name: "email"
        in: "query"
        description: "The user email for login"
        required: true
        type: "string"
      - name: "user_password"
        in: "query"
        description: "The password for login in clear text"
        required: true
        type: "string"
      responses:
        "200":
          description: "successful operation"
        "400":
          description: "Invalid username/password supplied"
            
  /api/delete_user:
    post:
      tags:
      - "api"
      summary: "Delete user record by ID"
      operationId: "deleteUser"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "user_id"
        in: "formData"
        description: "ID of the user that needs to be deleted"
        required: true
        type: "integer"
        format: "int64"
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      responses:
        "200":
          description: "successful Delete record"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "User not found"
            
  /api/update_user:
    post:
      tags:
      - "api"
      summary: "Update Registeration for all Users"
      operationId: "UpdateUser"
      consumes:
      - "multipart/form-data"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "user_id"
        in: "formData"
        description: "ID of the user that needs to be updated "
        required: true
        type: "string"
      - name: "first_name"
        in: "formData"
        description: "The first name of user"
        required: true
        type: "string"
      - name: "last_name"
        in: "formData"
        description: "The last name of user"
        required: true
        type: "string"
      - name: "mobile_no"
        in: "formData"
        description: "Mobile number for registration"
        required: true
        type: "number"
      - name: "address"
        in: "formData"
        description: "The password for register in clear text"
        required: false
        type: "string"
      - name: "blood_group"
        in: "formData"
        description: "User blood group "
        required: false
        type: "string"
      - name: "dob"
        in: "formData"
        description: "User date of birth "
        required: false
        type: "string"
      - name: "age"
        in: "formData"
        description: "Enter age of user"
        required: false
        type: "string"
      - name: "gender"
        in: "formData"
        description: "Gender of user "
        required: false
        type: "string"
      - name: "marital_status"
        in: "formData"
        description: "Marital status of user"
        required: false
        type: "string"
      - name: "license_type"
        in: "formData"
        description: "Type of user license"
        required: false
        type: "string"
      - name: "license_number"
        in: "formData"
        description: "License number of user"
        required: false
        type: "string"
      - name: "license_exp"
        in: "formData"
        description: "Expire date of user license"
        required: false
        type: "string"
      - name: "license_file"
        in: "formData"
        description: "License image to upload"
        required: false
        type: "file"
      responses:
        "200":
          description: "successful operation"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "ID not found"
          
  /api/update_status:
    post:
      tags:
      - "api"
      summary: "Update user status "
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "user_id"
        in: "formData"
        description: "ID of the user that needs to be updated "
        required: true
        type: "string"
      - name: "status"
        in: "formData"
        description: "status of user that needs to be updated "
        required: true
        type: "string"
      responses:
        "200":
          description: "Successfully, update status!"
        "400":
          description: "Failed to update status!"
        "404":
          description: "ID not found"
          
  /api/staff_attendance:
    post:
      tags:
      - "api"
      summary: "Attendance of all staff"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "user_id"
        in: "formData"
        description: "ID of the user for attendance"
        required: true
        type: "string"
      - name: "action_type"
        in: "formData"
        description: "Login or Logout user"
        required: true
        type: "string"
      responses:
        "200":
          description: "Thank you for your attendance!!"
        "400":
          description: "Failed to update attendance!"
        "404":
          description: "ID not found"
          
  /api/findStaffAttendance:
    post:
      tags:
      - "api"
      summary: "Find staff attendance"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "from_date"
        in: "formData"
        description: "Select from date to find staff attendance"
        required: true
        type: "string"
      - name: "to_date"
        in: "formData"
        description: "Select to date to find staff attendance"
        required: true
        type: "string"
      responses:
        "200":
          description: "Thank you for your attendance!!"
        "400":
          description: "Failed to update attendance!"
        "404":
          description: "ID not found"
          
  /api/staffLeave:
    post:
      tags:
      - "api"
      summary: "Staff leave application"
      operationId: "staffLeave"
      consumes:
      - "multipart/form-data"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "user_id"
        in: "formData"
        description: "User who want to leave."
        required: false
        type: "string"
      - name: "subject"
        in: "formData"
        description: "Enter subject for leave."
        required: false
        type: "string"
      - name: "message"
        in: "formData"
        description: "Enter message for leave."
        required: false
        type: "string"
      - name: "leave_img"
        in: "formData"
        description: "Upload document for leave application"
        required: false
        type: "file"
      responses:
        "200":
          description: "Successfully applied your leave!"
        "400":
          description: "Failed to apply leave!"
        "404":
          description: "Failed to apply leave!"
          
  /api/staffLeaveStatus:
    post:
      tags:
      - "api"
      summary: "Staff leave application"
      operationId: "LeaveStatus"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID who approved user status!"
        required: true
        type: "string"
      - name: "status"
        in: "formData"
        description: "Change user status!"
        required: true
        type: "number"
      responses:
        "200":
          description: "Successfully change leave status!"
        "400":
          description: "Failed to change leave status!"
          
  /api/patient_list:
    post:
      tags:
      - "api"
      summary: "Patient list by unique Id"
      operationId: "PatientList"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "unique_id"
        in: "formData"
        description: "user unique id"
        required: true
        type: "string"
      responses:
        "200":
          description: "Successfully find data !"
        "400":
          description: "Failed to find data !"
          
  /api/treatmentInstruction:
    post:
      tags:
      - "api"
      summary: "Treatment instruction for patient."
      operationId: "treatmentInstruction"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "before_treatment"
        in: "formData"
        description: "Before treatment, instruction for patient!"
        required: false
        type: "string"
      - name: "after_treatment"
        in: "formData"
        description: "After treatment, instruction for patient!"
        required: false
        type: "string"
      responses:
        "200":
          description: "Successfully change leave status!"
        "400":
          description: "Failed to change leave status!"
          
  /api/editTreatmentInstruction:
    post:
      tags:
      - "api"
      summary: "Update Treatment Instruction for patient"
      operationId: "editTreatmentInstruction"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "Before treatment, instruction for patient!"
        required: true
        type: "string"
      - name: "before_treatment"
        in: "formData"
        description: "Before treatment, instruction for patient!"
        required: false
        type: "string"
      - name: "after_treatment"
        in: "formData"
        description: "After treatment, instruction for patient!"
        required: false
        type: "string"
      responses:
        "200":
          description: "Successfully change leave status!"
        "400":
          description: "Failed to change leave status!"
      
        
  /api/deleteTreatmentInstruction:
    post:
      tags:
      - "api"
      summary: "Delete Treatment Instruction by ID"
      operationId: "deleteTreatmentInstruction"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID of the user that needs to be deleted"
        required: true
        type: "integer"
        format: "int64"
      responses:
        "200":
          description: "successful Delete record"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "User not found"
          
  /api/ethicsPolicy:
    post:
      tags:
      - "api"
      summary: "Add professional ethics policy and rules!"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "ethicsPolicy"
        in: "formData"
        description: "Enter your rules!"
        required: true
        type: "string"
      responses:
        "200":
          description: "Successfully, inserted Ethics Policy Rules!"
        "400":
          description: "Failed to change leave status!"
          
  /api/deleteEthicsPolicy:
    post:
      tags:
      - "api"
      summary: "Delete professional ethics policy and rules"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID of the rules that needs to be deleted"
        required: true
        type: "integer"
        format: "int64"
      responses:
        "200":
          description: "successful Delete record"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "User not found"
          
  /api/clinic_papers:
    post:
      tags:
      - "api"
      summary: "Clinic papers for patients!"
      operationId: "UploadClinicPapers"
      consumes:
      - "multipart/form-data"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "title"
        in: "formData"
        description: "Add title for your paper details!"
        required: false
        type: "string"
      - name: "paper_notes"
        in: "formData"
        description: "Paper notes for patient!"
        required: true
        type: "string"
      - name: "clinic_files"
        in: "formData"
        description: "Upload clinic_files!"
        required: false
        type: "file"
      
      responses:
        "200":
          description: "Successfully add all clinic papers!"
        "400":
          description: "Failed to add clinic papers!"
          
  /api/editClinic_papers:
    post:
      tags:
      - "api"
      summary: "Update clinic papers!"
      operationId: "EditClinicPapers"
      consumes:
      - "multipart/form-data"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID that needs to be updated !"
        required: false
        type: "string"
      - name: "title"
        in: "formData"
        description: "Add title for your paper details!"
        required: false
        type: "string"
      - name: "paper_notes"
        in: "formData"
        description: "Paper notes for patient!"
        required: true
        type: "string"
      - name: "clinic_files"
        in: "formData"
        description: "Upload clinic_files!"
        required: false
        type: "file"
      responses:
        "200":
          description: "Successfully update all clinic papers!"
        "400":
          description: "Failed to update clinic papers!"
    
  /api/deleteClinic_papers:
    post:
      tags:
      - "api"
      summary: "Delete clinic papers"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID that needs to be deleted"
        required: true
        type: "integer"
        format: "int64"
      responses:
        "200":
          description: "successful Delete record"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "ID not found"
          
  /api/bookAppointment:
    post:
      tags:
      - "api"
      summary: "Book your appointment"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "specialist"
        in: "formData"
        description: "Select specialist name!"
        required: true
        type: "string"
      - name: "doctor"
        in: "formData"
        description: "Select doctor name!"
        required: true
        type: "string"
      - name: "message"
        in: "formData"
        description: "Add message for doctor!"
        required: false
        type: "string"
      - name: "appointment_date"
        in: "formData"
        description: "Schedule appointment date!"
        required: false
        type: "string"
      - name: "appointment_time"
        in: "formData"
        description: "Schedule appointment time!"
        required: false
        type: "string"
      responses:
        "200":
          description: "successful booked appointment"
        "400":
          description: "Failed to booking appointment"
        "404":
          description: "ID not found"
          
  /api/deleteAppointment:
    post:
      tags:
      - "api"
      summary: "Delete patient appointment."
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID that needs to be deleted"
        required: true
        type: "integer"
        format: "int64"
      responses:
        "200":
          description: "successful Delete record"
        "400":
          description: "Invalid ID supplied"
        "404":
          description: "ID not found"
          
  /api/updateAppointment:
    post:
      tags:
      - "api"
      summary: "Update patient appointment!"
      operationId: "UpdateAppointment"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID that needs to be updated !"
        required: true
        type: "string"
      - name: "specialist"
        in: "formData"
        description: "Select specialist name!"
        required: false
        type: "string"
      - name: "doctor"
        in: "formData"
        description: "Select doctor name!"
        required: false
        type: "string"
      - name: "message"
        in: "formData"
        description: "Add message for doctor!"
        required: false
        type: "string"
      - name: "appointment_date"
        in: "formData"
        description: "Schedule appointment date!"
        required: false
        type: "string"
      - name: "appointment_time"
        in: "formData"
        description: "Schedule appointment time!"
        required: false
        type: "string"
      
      responses:
        "200":
          description: "Successfully update all clinic papers!"
        "400":
          description: "Failed to update clinic papers!"
  
  /api/changeAppointmentStatus:
    post:
      tags:
      - "api"
      summary: "Change Appointment Status"
      operationId: "StatusUpdate"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "id"
        in: "formData"
        description: "ID to change appointment status!"
        required: true
        type: "number"
      - name: "approvedBy"
        in: "formData"
        description: "Who approve appointment status!"
        required: true
        type: "string"
      - name: "status"
        in: "formData"
        description: "Change appointment status!"
        required: true
        type: "string"
      responses:
        "200":
          description: "Successfully change appointment status!"
        "400":
          description: "Failed to change appointment status!"
          
  /api/aptListForDoctor:
    post:
      tags:
      - "api"
      summary: "Doctor can check all appointment!"
      operationId: "bookedAptList"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "doctor"
        in: "formData"
        description: "enter doctor id"
        required: true
        type: "string"
      responses:
        "200":
          description: "Successfully find appointment list !"
        "400":
          description: "Failed to find appointment list !"
          
  /api/aptListForPatient:
    post:
      tags:
      - "api"
      summary: "Patient can check all appointment!"
      operationId: "appointmentList"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "patient_id"
        in: "formData"
        description: "user unique id"
        required: true
        type: "number"
      responses:
        "200":
          description: "Successfully find appointment list !"
        "400":
          description: "Failed to find appointment list !"
          
  "/api/doctorAvailability": {
      "post": {
        "tags": [
          "api"
        ],
        "summary": "Add doctor availability",
        "operationId": "DoctorAvailability",
        "produces": [
          "application/json"
        ],
        "parameters": [
          {
            "in": "header",
            "name": "Authorization",
            "description": "Logged in user token",
            "required": true,
            "type": "string"
          },
          {
            "in": "body",
            "name": "availabilityData",
            "description": "Doctor availability",
            "required": false,
            "schema": {
              "type": "object",
            }
          }
        ],
        "responses": {
          "default": {
            "description": "successfully operation"
          }
        }
      }
    }
          
  /api/getDoctorAvailability:
    post:
      tags:
      - "api"
      summary: "Doctor Availability day and time details!"
      operationId: "DoctorAvailabilityList"
      consumes:
      - "application/x-www-form-urlencoded"
      produces:
      - "application/json"
      parameters:
      - name: "Authorization"
        in: "header"
        description: "Logged in user token"
        required: true
        type: "string"
      - name: "doctor_id"
        in: "formData"
        description: "doctor ID"
        required: true
        type: "number"
      responses:
        "200":
          description: "Successfully find appointment list !"
        "400":
          description: "Failed to find appointment list !"

 
