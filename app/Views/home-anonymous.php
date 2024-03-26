<div class="col-md-12">
    <div class="card">
    Diese App stellt Funktionen für Mitglieder und Funktionäre im Verein zur Verfügung. Bitte melde Dich an, um die App nutzen zu können.
    </div>
</div>

<div class="col-md-6">
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Benutzeranmeldung</h3>
  </div>
  <form method='POST' action='/'>
    <div class="card-body">
      <div class="form-group">
        <label for="loginform-usr">E-Mail</label>
        <input type="text" class="form-control" id="loginform-usr" name="loginform-usr" placeholder="Benutzername eingeben">
      </div>
      <div class="form-group">
        <label for="loginform-pwd">Passwort</label>
        <input type="password" class="form-control" name='loginform-pwd' id="loginform-pwd" placeholder="Passwort eingeben">
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-outline-primary btn-block"><i class="fa fa-sign-in-alt"></i> anmelden</button>
    </div>
  </form>
</div>
</div>