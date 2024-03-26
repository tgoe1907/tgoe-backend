<?php
use App\Libraries\DataQualityCheckResult;
use TgoeSrv\Member\Validator\ValidationMessage;

/**
 *
 * @var DataQualityCheckResult $result
 */
?>
<form method='POST' action='/account/passwd'>
<div class="card">
	<div class="card-header">
    	Das neue Passwort muss folgende Bedingungen erfüllen:
        <ul>
        	<li>Ist mindestens 8 Zeichen lang.</li>
        	<li>Enthält mindestens einen Kleinbuchstaben.</li>
        	<li>Enthält mindestens einen Großbuchstaben.</li>
        	<li>Enthält mindestens eine Ziffer.</li>
        	<li>Enthält mindestens ein Sonderzeichen.<li>
        </ul>
    </div>
    <div class="card-body">
      <div class="form-group">
        <label for="current-pwd">Aktuelles Passwort</label>
        <input type="password" class="form-control" name='current-pwd' id="current-pwd" placeholder="Passwort eingeben">
      </div>    
      <div class="form-group">
        <label for="new-pwd">Neues Passwort</label>
        <input type="password" class="form-control" name='new-pwd' id="new-pwd" placeholder="Passwort eingeben">
      </div>
      <div class="form-group">
        <label for="confirm-pwd">Wiederholung des neuen Passworts</label>
        <input type="password" class="form-control" name='confirm-pwd' id="confirm-pwd" placeholder="Passwort eingeben">
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-outline-primary btn-block"><i class="fa fa-save"></i> Passwort ändern</button>
    </div>
</div>
</form>