<div class="col-md-12">
    <div class="card">
        Die Zusammenstellung der Listen kann bis zu 1 Minute dauern.<br />
        Nur 1x auf den Download Link klicken und warten. Zu viele parallele Anfragen können die kurzzeitige Sperrung der Verbindung zu Easyverein auslösen.<br />
    </div>
    <div class="card">
    	<b>Empfängerliste Jahrbuch</b><br />
        <a href="/admin/export-lists/yearbook-recipients">CSV exportieren</a>
	</div>
    <div class="card">
    	<b>Geburtstagsliste</b><br />
    	für das Jahr<br />
    	<input id="birthdayyear" type="text" value="<?= date("Y") ?>"><br />
    	für diese Geburtstage (leer für alle)<br />
    	<input id="birthdays" type="text" value="65,70,75,80,85,90,95,100,105,110"><br />
        <a onclick="downloadBirthday()" href="#">CSV exportieren</a>
        <script>
        	function downloadBirthday() {
        		window.location = "/admin/export-lists/birthday/" + document.getElementById("birthdayyear").value + "/" + document.getElementById("birthdays").value;
    		}
        </script>
	</div>
    <div class="card">
    	<b>Jubiläumsliste</b><br />
    	für das Jahr<br />
    	<input id="jubileeyear" type="text" value="<?= date("Y") ?>"><br />
    	für diese Jubiläen (leer für alle)<br />
    	<input id="jubilees" type="text" value="25,40,50,60,70,80,90,100,110"><br />
        <a onclick="downloadJubilee()" href="#">CSV exportieren</a>
        <script>
        	function downloadJubilee() {
        		window.location = "/admin/export-lists/jubilee/" + document.getElementById("jubileeyear").value + "/" + document.getElementById("jubilees").value;
    		}
        </script>
	</div>		
</div>

<div class="col-md-6">
</div>