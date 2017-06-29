<form action="" class="waste-art-container-form" data-emails="<?php echo $params['emails'];?>">
    <div class="container">
        <div class="row waste-art-container-form-step-1">
            <div class="col-md-8">
                <?php
                if(isset($_GET['container-size'])){
                    echo "<div class='preset-param'>";
	                $str = parse_str($_SERVER['QUERY_STRING'], $output);
                   if(!is_array($output) && empty($output)) return;
                   if(isset($output['abfallart'])){
                       echo 'Abfallart: '.$output['abfallart'];
                       echo '<input type="hidden" class="waster-art-input" name="abfallart" value="'.$output['abfallart'].'">';
                       echo '<br/>';
                   }


	                if(isset($output['container'])){
		                echo 'Container: '.$output['container'];
		                echo '<input type="hidden" class="waster-art-input" name="container" value="'.$output['container'].'">';
		                echo '<br/>';
	                }

	                if(isset($output['container-size'])){
		                echo 'Containergröße:: '.$output['container-size'];
		                echo '<input type="hidden" class="waster-art-input" name="container-size" value="'.$output['container-size'].'">';
		                echo '<br/>';
	                }

	                echo '</div>';

                }
                ?>


                <div class="lbl-inp">
                    <label for="" class="lbl">Kundennummer</label>
                    <input type="text" class="waster-art-input" name="kundennummer" placeholder="… falls vorhanden, bitte ausfüllen. Ansonsten 0 eintragen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Besteller/in</label>
                    <input type="text" dirname="besteller" class="waster-art-input" name="besteller" placeholder="… bitte ausfüllen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Firma/Name</label>
                    <input type="text" name="company" class="waster-art-input" placeholder="… bitte ausfüllen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Straße</label>
                    <input type="text" name="state" class="waster-art-input" placeholder="… bitte ausfüllen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Plz./Ort</label>
                    <div class="dublbox">
                        <input type="text" name="zip" class="waster-art-input" placeholder="… bitte ausfüllen." required>
                        <input type="text" name="city" class="waster-art-input" placeholder="… bitte ausfüllen." required>
                    </div>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Telefon</label>
                    <input type="text" class="waster-art-input" name="phone" placeholder="… bitte ausfüllen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">e-Mail</label>
                    <input type="email" class="waster-art-input" name="email" placeholder="… bitte ausfüllen." required>
                </div>
                <div class="lbl-inp">
                    <label for="" class="lbl">Detum</label>
                    <input type="datetime" class="waster-art-input waste-date-picker ui-datepicker-trigger" name="date" placeholder="… bitte ausfüllen." required>
                </div>
            </div>
            <div class="col-md-4">
                <input class="waste-art-form-submit button" type="submit" value="Auftrag abschicken">
                <p>Sie erhalten auf der nächsten
                    Seite die Möglichkeit
                    Ihre Bestellung erneut zu
                    überprüfen.</p>


                <input type="button"  class="button print-form-btn" value="Auftrag drucken">
                <p>
                    Bei Fragen oder telefonischer
                    Bestellung bitte <a href="/anfrage-umweltservice/">hier</a> klicken.
                </p>
            </div>
        </div>

        <div class="row waste-art-container-form-step-2" style="display: none;">
            <div class="col-md-12">
                <div class="waste-form-confirmation">
                    <span class="waste-art-confirmation-line"><strong>Customer Number:</strong> <span class="waste-art-kundennummer"></span> <strong><span class="waste-art-saved-customer-name"></span></strong></span>
                    <span class="waste-art-confirmation-gap"></span>
                    <span class="waste-art-confirmation-line"><span>VOCUS-Umleercontainer:</span> <span class="waste-art-container-size"></span></span>
                    <span class="waste-art-confirmation-line"><span>Abfallart:</span> <span class="waste-art-abfallart"></span></span>
                    <span class="waste-art-confirmation-line"><span>Container:</span> <span class="waste-art-container"> </span></span>
                    <span class="waste-art-confirmation-line"> <span>Date:</span> <span class="waste-art-date"></span></span>
                    <span class="waste-art-confirmation-gap"></span>
                    <span class="waste-art-confirmation-line"><span class="waste-art-besteller"></span></span>
                    <span class="waste-art-confirmation-line"><span class="waste-art-company"></span></span>
                    <span class="waste-art-confirmation-line"><span class="waste-art-state"></span></span>
                    <span class="waste-art-confirmation-line"><span class="waste-art-zip"></span>   <span class="waste-art-city"></span></span>
                    <span class="waste-art-confirmation-gap"></span>
                    <span class="waste-art-confirmation-line">Telefon <span class="waste-art-phone"></span></span>
                    <span class="waste-art-confirmation-line">E-Mail <span class="waste-art-email"></span></span>

                </div>
                <div class="declaimer-line"><input type="checkbox" class="check-confirm"> Ich habe die  <?php AnythingPopup( $pop_id = "1" ); ?> gelesen und akzeptiert.</div>

                <input type="button" class=" button waste-submit-form" value="Kostenpflichtig in Auftrag geben" class="button">
                <input type="button" value="Ändern" class="button waster-art-form-edit-btn">
            </div>
        </div>

        <div class="row waste-art-container-form-step-3" style="display: none;">
           <strong>Vielen Dank für ihren Auftrag.</strong><br>
                Sie erhalten in Kürze eine Auftragsbestätigung per Mail.<br><br>
                <a id="backhome" class="subbtn button" href="<?php echo get_site_url();?>">zurück zur Startseite</a>
        </div>
    </div>
</form>