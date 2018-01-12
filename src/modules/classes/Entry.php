<?php

namespace HealthChain\modules\classes;

use DateTime;

class Entry
{
    public $who;
    public $date;
    public $comment;
    public $attachments;

    public function __construct()
    {
        $this->who = '';
        $this->date = new DateTime();
        $this->comment = '';
        $this->attachments = [];
    }

    public function renderDate() {
        return $this->date->format('d/m/o');
    }

    public function renderAttachments() {
        return '<ul>
<li>attachement 1</li>
<li>attachement 2</li>
</ul>';
    }

    public function renderAddForm() {
        $html = <<<EOS
<form>
  <div class="form-group">
    <label for="doctor_name">Doctor Name</label>
    <input type="email" class="form-control" id="doctor_name" placeholder="Dr Schmidt">
  </div>
  
  <div class="form-group">
    <label for="doctor_speciality">Example select</label>
    <select class="form-control" id="doctor_speciality">
      <option>General Medicine</option>
      <option>Other</option>
    </select>
  </div>
  
   <div class="form-group">
    <label for="comment">Example textarea</label>
    <textarea class="form-control" id="comment" rows="3"></textarea>
  </div>
  
  <div class="form-group">
    <label for="comment">Attachments</label>
  </div>
  
    
  
  
   <button type="submit" class="btn btn-primary">Submit</button>
   
</form>

<form action="/file-upload"
      class="dropzone"
      id="my-awesome-dropzone">
      <div class="fallback" style="border:2px solid red">
    <input name="file" type="file" multiple />
  </div>
</form>

EOS;

        return $html;

    }
}