<?php


// List all available csv files for display porposes (selection list, see below)
function csvfiles() {
 	global $csvfile;
	$files = glob("./data/*.csv");
    foreach($files as $file) {
			if ($csvfile == $file) {
  				print("<option selected value=\"".$file."\">".$file."</option>");
			} else {
	  			print("<option value=\"".$file."\">".$file."</option>");
			}
    }

}
?>

<form id="form1" name="form2" method="post" action="">
  <table width="400" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="212"><div align="right">Passwort</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="pw" type="password" id="pw" value="<?php print($pw);?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Neues Passwort</div></td>
      <td>&nbsp;</td>
      <td><input name="npw1" type="password" id="pw" value=""/></td>
    </tr>
    <tr>
      <td><div align="right">Neues Passwort (Wiederholung)</div></td>
      <td>&nbsp;</td>
      <td><input name="npw2" type="password" id="pw" value=""/></td>
    </tr>
    <tr>
      <td><div align="right">Maximale Personen</div></td>
      <td>&nbsp;</td>
      <td><input name="nmaxnum" type="text" id="nmaxnum" value="<?php print($maxnum);?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Uhrzeitgrenze Sonntag</div></td>
      <td>&nbsp;</td>
      <td><input name="nswitchtime" type="text" id="nswitchtime" value="<?php print($switchtime);?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Banner Information</div></td>
      <td>&nbsp;</td>
      <td><input name="nbanner" type="text" id="nbanner" value="<?php print($banner);?>"/></td>
    </tr>
    <tr>
      <td><div align="right">Covid Regeln Link</div></td>
      <td>&nbsp;</td>
      <td><input name="ncoronalink" type="text" id="ncoronalink" value="<?php print($coronalink);?>"/></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="save" value="Speichern" />
      </label></td>
    </tr>
  </table>
  <p><br>
    <br>
    <?php
print("Aktuelle Datei (f&uuml;r User): ".currentFile()."<BR>");
?>
    <br>
    <br>
      <select name="csvfile" id="csvfile" onchange="this.form.submit();">
        <?php csvfiles() ?>
      </select>
    <input type="submit" name="load" value="Laden" />
    <input type="submit" name="savefile" value="Speichern" />
    <input type="submit" name="download" value="Download" />
    <input type="submit" name="mobile" value="Mobile" />
    <input type="submit" name="print" value="Drucken" />
    <?php print(getLinesFile($csvfile)." Zeilen"); ?>
    <br>
      <textarea name="filecontent" cols="100" rows="18" wrap="no" style="overflow:scroll;"><?php print($filecontent)?></textarea>
  </p>
  <p>&nbsp;</p>
  <b>Spezialtermin</b>
  <table width="400" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="212"><div align="right">Datum des Termins</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="d" type="text" id="d" placeholder="25.12.2020" value=""/></td>
    </tr>
    <tr>
      <td width="212"><div align="right">Maximale Personen</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="dmax" type="text" id="dmax" placeholder="42" value=""/></td>
    </tr>
    <tr>
      <td width="212"><div align="right">Anmeldung bis Datum <br>und Uhrzeit</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="dday" type="text" id="dday" placeholder="23.12.2020" value=""/> <input name="dtime" type="text" id="dtime" placeholder="23:00" value=""/></td>
    </tr>
    <tr>
      <td width="212"><div align="right">Titel</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="dtitle" type="text" id="dtitle" placeholder="Weihnachts-GODI um 15:30 Uhr" value=""/></td>
    </tr>
    <tr>
      <td width="212"><div align="right">Sichtbares Datum und Uhrzeit (Joker)</div></td>
      <td width="17">&nbsp;</td>
      <td width="171"><input name="djoker" type="text" id="djoker" placeholder="24.12.2020 15:30" value=""/></td>
    </tr>
    <tr>
      <td><div align="right"></div></td>
      <td>&nbsp;</td>
      <td><input name="specialdatesubmit" type="submit" id="specialdatesubmit"  value="Liste anlegen/&auml;ndern" /></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p></</p>
