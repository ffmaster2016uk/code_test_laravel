<?php

namespace App;

class ContactDirectory extends ContactModule
{

    use Validators;

    /**
     * private property, a list of available contacts
     * @access private
     * @var array
     */

    private $_contacts = [];

    /**
     * private property, a list of favourite contacts
     * @access private
     * @var array
     */

    private $_favourites = [];

    /**
     * private property, src file name to load and store contacts
     * @access private
     * @var string
     */

    private $_contacts_src = 'contacts.json';

    /**
     * private property, src file name to load and store favourite contacts
     * @access private
     * @var string
     */

    private $_favourites_src = 'favourites.json';


    /**
     * class constant, folder name for the data directory
     * @access private
     * @var string
     */

    const DATA_DIR = 'data';

    /**
     * private property, a list of properties a contact object should have
     * @access private
     * @var array
     */

    private static $_expected_contact_properties = [
        'forename',
        'surname',
        'email',
        'address',
        'telephone',
    ];

    /**
     * constructor, loads in the contact and favourite list
     *
     */

    protected function __construct() {

        $src_file = public_path() . DIRECTORY_SEPARATOR . self::DATA_DIR . DIRECTORY_SEPARATOR . $this->_contacts_src;
        $fav_file = public_path() . DIRECTORY_SEPARATOR . self::DATA_DIR . DIRECTORY_SEPARATOR . $this->_favourites_src;

        if(file_exists($src_file))
            $this->load($src_file, '_contacts');

        else {

            file_put_contents($src_file, '[]');
            $this->_errors = ['ERROR_001' => 'Contact data file not found, created blank new file'];
        }


        if(file_exists($fav_file))
            $this->load($fav_file, '_favourites');
        else {
            file_put_contents($fav_file, '[]');
            $this->_errors = ['ERROR_005' => 'Favourites data file not found, created blank new file'];
        }

    }

    /**
     * validation wrapper
     *
     * @mvc Controller
     *
     * @param  string $name
     * @param  mixed $value
     * @return boolean
     */

    public static function validation ($name, $value) {
        $valid = false;
        switch ($name) {
            case 'contact_data':
                $valid = self::validContactData($value);
                break;
            case 'search_input':
                $valid = self::none_empty_string($value);
                break;
            case 'data_array':
                $valid = is_array($value);
                break;
        }

        return $valid;
    }

    /**
     * validation for the contact data object
     *
     * @mvc Controller
     *
     * @param  object $contact_data
     * @return boolean
     */

    public static function validContactData($contact_data) {

        $valid = true;

        if(is_object($contact_data)) {
            foreach (self::$_expected_contact_properties as $property) {
                if(!property_exists($contact_data, $property)) {
                    $valid = false;
                }
                else {
                    switch ($property) {
                        case 'forename':
                        case 'surname':
                        case 'address':
                        case 'telephone':
                            $valid = self::none_empty_string($contact_data->$property);
                            break;
                        case 'email':
                            $valid = self::valid_email($contact_data->$property);
                            break;
                        default:
                            $valid = false;
                    }
                }
                if(!$valid) return $valid;
            }
        }
        else {
            $valid = false;
        }

        return $valid;

    }

    /**
     * Load contact resource store to the field specified
     *
     * @mvc Model
     *
     * @param string $src_file_dir
     * @param string $store_to
     */

    public function load($src_file_dir, $store_to) {

        $data_string = file_get_contents($src_file_dir);
        $data        = json_decode($data_string);
        $valid_data  = [];

        if(json_last_error() == JSON_ERROR_NONE && self::validation('data_array', $data)) {
            foreach($data as $contact_data) {
                if(self::validation('contact_data', $contact_data))
                    $valid_data[] = $contact_data;
                else {
                    $this->_errors = [
                        'ERROR_003' => [
                            'message' => 'Contact data contain invalid field data',
                            'data'    => $contact_data
                        ]
                    ];
                }

            }
            $this->$store_to = $valid_data;
        }
        else
            $this->_errors = ['ERROR_002' => 'Contact source file data structure is wrong'];

    }

    /**
     * save contact/favourite list to destination file
     *
     * @mvc Model
     *
     * @param string $save_from
     * @param string $src_file_dir
     */

    public function save($save_from, $src_file_dir) {

        if(file_exists($src_file_dir)) {
            $data = json_encode($this->$save_from, JSON_PRETTY_PRINT);
            file_put_contents($src_file_dir, $data);
        }

    }

    /**
     * search for contact based on post input, return the matched contacts in an array
     *
     * @mvc Controller
     *
     * @param  array $post
     * @return array
     */

    public function search($post) {

        $result_data = [];
        if(self::validation('search_input', $post['lookup'])) {
            foreach($this->_contacts as $contact) {
                foreach($contact as $property => $value) {
                    if(strpos(strtolower($value), strtolower($post['lookup'])) !== false) {
                        $result_data[] = $contact;
                    }
                }
            }
        }
        else {
            $this->_errors = ['ERROR_008' => 'Invalid search input'];
        }

        return $result_data;

    }

    /**
     * add a validated contact to contact list and save to contact source file
     *
     * @mvc Model
     *
     * @param array $post
     *
     */

    public function addContact($post) {

        $contact = (object) array_intersect_key($post, array_flip(self::$_expected_contact_properties));

        if(self::validation('contact_data', $contact)) {
            if(!$this->isDuplicateContact($contact, '_contacts')) {
                $this->_contacts[] = $contact;
                $src_file = public_path() . DIRECTORY_SEPARATOR . self::DATA_DIR . DIRECTORY_SEPARATOR . $this->_contacts_src;
                $this->save('_contacts', $src_file);
            }
        }
        else {
            $this->_errors = [
                'ERROR_006' => [
                    'message' => 'Failed to Save contact, contact data not valid',
                    'data'    => $contact
                ]
            ];
        }

    }

    /**
     * add a validated favourite contact to favourite list and save to favourite source file
     *
     * @mvc Model
     *
     * @param string $contact_json_string
     */

    public function addFavouriteContact($contact_json_string) {

        $contact = json_decode($contact_json_string);

        if(json_last_error() == JSON_ERROR_NONE && self::validation('contact_data', $contact)) {
            if(!$this->isDuplicateContact($contact, '_favourites')) {
                $this->_favourites[] = $contact;
                $fav_file = public_path() . DIRECTORY_SEPARATOR . self::DATA_DIR . DIRECTORY_SEPARATOR . $this->_favourites_src;
                $this->save('_favourites', $fav_file);
            }

        }
        else {
            $this->_errors = [
                'ERROR_007' => [
                    'message' => 'Failed to Save favourite contact, contact data not valid',
                    'data'    => $contact
                ]
            ];
        }

    }

    /**
     * check if a contact already exists
     *
     * @mvc Controller
     *
     * @param  object $contact
     * @param  string $to_check
     * @return boolean
     */

    public function isDuplicateContact($contact, $to_check) {

        foreach($this->$to_check as $exiting_contact) {
            if($exiting_contact == $contact) {
                return true;
            }
        }

        return false;
    }

    /**
     * returns a list of stored contacts
     *
     * @mvc Controller
     *
     */

    public function getContacts() {
        return $this->_contacts;
    }

    /**
     * returns a list of stored favourite contacts
     *
     * @mvc Controller
     *
     */

    public function getFavourites() {
        return $this->_favourites;
    }

    /**
     * returns a list of stored favourite contacts
     *
     * @mvc Controller
     *
     */

    public function getErrors() {
        return $this->_errors;
    }
}



