<?php

namespace HealthChain\modules\traits;

use HealthChain\modules\classes\User;

trait FormTrait
{

    public function renderFieldDoctorName() {
        return '<div class="form-group required ">
                    <label for="doctor_name">Doctor Name *</label>
                    <input type="text" class="form-control" id="doctor_name" name="doctor_name" placeholder="Dr Schmidt">
                </div>';
    }

    public function renderFieldDoctorSpeciality() {
        return '<div class="form-group required ">
                    <label for="doctor_speciality">Speciality *</label>
                    <select class="form-control custom-select" id="doctor_speciality" name="doctor_speciality">
                        <option value="default">-- Please Choose --</option>
                        <optgroup label="Type of doctor">
                            <option>General Medicine</option>
                            <option>Other</option>
                        </optgroup>
                        <optgroup label="Relatives">
                            <option>Parent</option>
                            <option>Me</option>
                        </optgroup>
                    </select>
                </div>';
    }

    public function renderFieldComment() {
        return '<div class="form-group required ">
                    <label for="comment">Comment *</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                </div>';
    }

    public function renderStarIsMandatory() {
        return '<div>
                    <i>Fields marked with a (*) are mandatory.</i>
                    <br /><br />
                </div>';
    }

    public function renderFieldDelegationTime() {
        return '<div class="form-group required ">
                    <label for="delegation_time">Period of delegation *</label>
                    <select class="form-control custom-select" id="delegation_time" name="delegation_time">
                        <option value="6">6 hours</option>
                        <option value="12">12 hours</option>
                        <option value="24">24 hours</option>
                        <option value="48">2 days</option>
                        <option value="120">5 days</option>
                    </select>
                </div>';
    }

    public function renderSubmitButton($text) {
        return '<button type="submit" class="btn btn-primary">'.$text.'</button>';
    }

    public function renderTerminateAccessButton() {
        global $directory;

        if (User::isUserDoctor()) {
            $html = <<<EOS
&nbsp;<button type="button" class="btn btn-danger" id="terminateAccess" onclick="window.location.href = '$directory/terminateAccess.html';">
<i class="fa fa-user-md mr-1"></i>Terminate access
</button>
EOS;
        }
        else {
            $html = '';
        }
        return  $html;
    }

}