<?
	/**
	 * language pack
	 * @author Logan Cai (cailongqun@yahoo.com.cn)
	 * @link www.phpletter.com
	 * @since 22/April/2007
	 *
	 */
	define('DATE_TIME_FORMAT', 'd/M/Y H:i:s');
	//Label
		//Top Action
		define('LBL_ACTION_REFRESH', 'Vernieuwen');
		define("LBL_ACTION_DELETE", 'Wissen');
		define('LBL_ACTION_CUT', 'Knippen');
		define('LBL_ACTION_COPY', 'Kopieren');
		define('LBL_ACTION_PASTE', 'Plakken');
		//File Listing
	define('LBL_NAME', 'Naam');
	define('LBL_SIZE', 'Grootte');
	define('LBL_MODIFIED', 'Gewijzigd:');
		//File Information
	define('LBL_FILE_INFO', 'Bestands informatie:');
	define('LBL_FILE_NAME', 'Naam:');	
	define('LBL_FILE_CREATED', 'Gemaakt:');
	define("LBL_FILE_MODIFIED", 'Gewijzigd:');
	define("LBL_FILE_SIZE", 'Bestandsgrootte:');
	define('LBL_FILE_TYPE', 'Bestandstype:');
	define("LBL_FILE_WRITABLE", 'Schrijven mogelijk?');
	define("LBL_FILE_READABLE", 'Lezen mogelijk?');
		//Folder Information
	define('LBL_FOLDER_INFO', 'Map informatie');
	define("LBL_FOLDER_PATH", 'Pad:');
	define("LBL_FOLDER_CREATED", 'Gemaakt:');
	define("LBL_FOLDER_MODIFIED", 'Gewijzigd:');
	define('LBL_FOLDER_SUDDIR', 'Submappen:');
	define("LBL_FOLDER_FIELS", 'Bestanden:');
	define("LBL_FOLDER_WRITABLE", 'Schrijven mogelijk?');
	define("LBL_FOLDER_READABLE", 'Lezen mogelijk?');
		//Preview
	define("LBL_PREVIEW", 'Preview');
	//Buttons
	define('LBL_BTN_SELECT', 'Selecteren');
	define('LBL_BTN_CANCEL', 'Annuleren');
	define("LBL_BTN_UPLOAD", 'Uploaden');
	define('LBL_BTN_CREATE', 'Creeren');
	define('LBL_BTN_CLOSE', 'Afsluiten');
	define("LBL_BTN_NEW_FOLDER", 'Nieuwe Map');
	define('LBL_BTN_EDIT_IMAGE', 'Wijzigen');
	//Cut
	define('ERR_NOT_DOC_SELECTED_FOR_CUT', 'Geen document(en) geselecteerd om te knippen.');
	//Copy
	define('ERR_NOT_DOC_SELECTED_FOR_COPY', 'Geen document(en) geselecteerd om te kopieren).');
	//Paste
	define('ERR_NOT_DOC_SELECTED_FOR_PASTE', 'Geen document(en) geselecteerd om te plakken.');
	define('WARNING_CUT_PASTE', 'Geselecteerde documenten naar huidige map verplaatsen?');
	define('WARNING_COPY_PASTE', 'Geselecteerde documenten naar huidige map kopieren?');
	
	//ERROR MESSAGES
		//deletion
	define('ERR_NOT_FILE_SELECTED', 'Selecteer een bestand.');
	define('ERR_NOT_DOC_SELECTED', 'Geen documenten geselecteerd om te wissen.');
	define('ERR_DELTED_FAILED', 'Wissen geselecteerde documenten niet gelukt.');
	define('ERR_FOLDER_PATH_NOT_ALLOWED', 'Het pad is niet toegestaan.');
		//class manager
	define("ERR_FOLDER_NOT_FOUND", 'Map niet gevonden: ');
		//rename
	define('ERR_RENAME_FORMAT', 'Naam mag alleen letters, cijfers, spaties, apostroph en laag streepje bevatten.');
	define('ERR_RENAME_EXISTS', 'Naam bestaat reeds in deze map, geef bestand een unieke naam.');
	define('ERR_RENAME_FILE_NOT_EXISTS', 'Het bestand/de map bestaat niet.');
	define('ERR_RENAME_FAILED', 'Hernoemen niet gelukt.');
	define('ERR_RENAME_EMPTY', 'Geef bestand een naam.');
	define("ERR_NO_CHANGES_MADE", 'Bestand is niet gewijzigd.');
	define('ERR_RENAME_FILE_TYPE_NOT_PERMITED', 'Bestandsextensie niet toegestaan.');
		//folder creation
	define('ERR_FOLDER_FORMAT', 'Naam mag alleen letters, cijfers, spaties, apostroph en laag streepje bevatten.');
	define('ERR_FOLDER_EXISTS', 'Map bestaat reeds, geef nieuwe map een unieke naam.');
	define('ERR_FOLDER_CREATION_FAILED', 'Creeren map niet gelukt.');
	define('ERR_FOLDER_NAME_EMPTY', 'Geef map een naam.');
	
		//file upload
	define("ERR_FILE_NAME_FORMAT", 'Naam mag alleen letters, cijfers, spaties, apostroph en laag streepje bevatten.');
	define('ERR_FILE_NOT_UPLOADED', 'Geen bestanden geslecteerd om te uploaden.');
	define('ERR_FILE_TYPE_NOT_ALLOWED', 'Uploaden bestandstype is niet toegestaan.');
	define('ERR_FILE_MOVE_FAILED', 'Verplaatsen bestand is niet gelukt.');
	define('ERR_FILE_NOT_AVAILABLE', 'Het bestand is niet beschikbaar.');
	define('ERROR_FILE_TOO_BID', 'Het bestand is te groot. (max: %s)');
	

	//Tips
	define('TIP_FOLDER_GO_DOWN', 'Klik om naar deze map te gaan...');
	define("TIP_DOC_RENAME", 'Dubbelklik om te wijzigen...');
	define('TIP_FOLDER_GO_UP', 'Klik om naar bovenliggende folder te gaan...');
	define("TIP_SELECT_ALL", 'Alles selecteren');
	define("TIP_UNSELECT_ALL", 'Alles deselecteren');
	//WARNING
	define('WARNING_DELETE', 'Geselecteerde bestanden verwijderen?');
	define('WARNING_IMAGE_EDIT', 'Selecteer een afbeelding om te wijzigen.');
	define('WARING_WINDOW_CLOSE', 'Venster sluiten?');
	//Preview
	define('PREVIEW_NOT_PREVIEW', 'Geen voorbeeld beschikbaar.');
	define('PREVIEW_OPEN_FAILED', 'Weergeven voorbeeld niet gelukt.');
	define('PREVIEW_IMAGE_LOAD_FAILED', 'Laden afbeelding niet gelukt');

	//Login
	define('LOGIN_PAGE_TITLE', 'Ajax File Manager Login Formulier');
	define('LOGIN_FORM_TITLE', 'Login Formulier');
	define('LOGIN_USERNAME', 'Gebruiker:');
	define('LOGIN_PASSWORD', 'Wachtwoord:');
	define('LOGIN_FAILED', 'Ongeldige gebruiker/wachtwoord.');
	
	
	//88888888888   Below for Image Editor   888888888888888888888
		//Warning 
		define('IMG_WARNING_NO_CHANGE_BEFORE_SAVE', "Afbeelding is niet gewijzigd.");
		
		//General
		define('IMG_GEN_IMG_NOT_EXISTS', 'Afbeelding bestaat niet');
		define('IMG_WARNING_LOST_CHANAGES', 'Niet opgeslagen wijzigingen gaan verloren, doorgaan?');
		define('IMG_WARNING_REST', 'Niet opgeslagen wijzigingen gaan verloren bij resetten, doorgaan?');
		define('IMG_WARNING_EMPTY_RESET', 'Afbeelding is niet gewijzigd');
		define('IMG_WARING_WIN_CLOSE', 'Venster sluiten?');
		define('IMG_WARNING_UNDO', 'Wijziging ongedaan maken?');
		define('IMG_WARING_FLIP_H', 'Afbeelding horizontaal spiegelen?');
		define('IMG_WARING_FLIP_V', 'Afbeelding verticaal spiegelen?');
		define('IMG_INFO', 'Afbeelding informatie');
		
		//Mode
			define('IMG_MODE_RESIZE', 'Grootte aanpassen:');
			define('IMG_MODE_CROP', 'Bijsnijden:');
			define('IMG_MODE_ROTATE', 'Roteren:');
			define('IMG_MODE_FLIP', 'Spiegelen:');		
		//Button
		
			define('IMG_BTN_ROTATE_LEFT', '90&deg;CCW');
			define('IMG_BTN_ROTATE_RIGHT', '90&deg;CW');
			define('IMG_BTN_FLIP_H', 'Horizontaal spiegelen');
			define('IMG_BTN_FLIP_V', 'Verticaal spiegelen');
			define('IMG_BTN_RESET', 'Reset');
			define('IMG_BTN_UNDO', 'Undo');
			define('IMG_BTN_SAVE', 'Opslaan');
			define('IMG_BTN_CLOSE', 'Afsluiten');
		//Checkbox
			define('IMG_CHECKBOX_CONSTRAINT', 'Verhoudingen?');
		//Label
			define('IMG_LBL_WIDTH', 'Breedte:');
			define('IMG_LBL_HEIGHT', 'Hoogte:');
			define('IMG_LBL_X', 'X:');
			define('IMG_LBL_Y', 'Y:');
			define('IMG_LBL_RATIO', 'Ratio:');
			define('IMG_LBL_ANGLE', 'Hoek:');
		//Editor

			
		//Save
		define('IMG_SAVE_EMPTY_PATH', 'Afbeeldingen-pad is leeg.');
		define('IMG_SAVE_NOT_EXISTS', 'Afbeelding bestaat niet.');
		define('IMG_SAVE_PATH_DISALLOWED', 'Geen machtiging om dit bestand te openen.');
		define('IMG_SAVE_UNKNOWN_MODE', 'Onverwachte afbeelding Operation Mode');
		define('IMG_SAVE_RESIZE_FAILED', 'Aanpassen grootte afbeelding niet gelukt.');
		define('IMG_SAVE_CROP_FAILED', 'Bijsnijden afbeelding niet gelukt.');
		define('IMG_SAVE_FAILED', 'Opslaan afbeelding niet gelukt.');
		define('IMG_SAVE_BACKUP_FAILED', 'Bewaren originele afbeelding niet gelukt.');
		define('IMG_SAVE_ROTATE_FAILED', 'Roteren afbeelding niet gelukt.');
		define('IMG_SAVE_FLIP_FAILED', 'Spiegelen afbeelding niet geslukt.');
		define('IMG_SAVE_SESSION_IMG_OPEN_FAILED', 'Openen afbeelding vanuit session niet gelukt.');
		define('IMG_SAVE_IMG_OPEN_FAILED', 'Openen afbeelding niet mogelijk');
		
		//UNDO
		define('IMG_UNDO_NO_HISTORY_AVAIALBE', 'Ongedaan maken geschiedenis niet mogelijk.');
		define('IMG_UNDO_COPY_FAILED', 'Ongedaan maken kopieren niet mogelijk.');
		define('IMG_UNDO_DEL_FAILED', 'Ongedaan maken verwijderen niet mogelijk.');
	
	//88888888888   Above for Image Editor   888888888888888888888
	
	//88888888888   Session   888888888888888888888
		define("SESSION_PERSONAL_DIR_NOT_FOUND", 'Map niet gevonden; deze zou aangemaakt moeten zijn in de session-map.');
		define("SESSION_COUNTER_FILE_CREATE_FAILED", 'Openen session counter-bestand niet gelukt.');
		define('SESSION_COUNTER_FILE_WRITE_FAILED', 'Schrijven naar session counter-bestand niet gelukt.');
	//88888888888   Session   888888888888888888888
	
	
?>