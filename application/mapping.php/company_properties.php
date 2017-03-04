<?php
    class company_properties_mapping
    {
        public $dataMappingArray;

        public function __construct()
        {
            $this->dataMappingArray = array(
                'companyPropertiesId'                       => array('columnName' => 'company_properties.id',				'default' => null),
                'companyPropertiesCompanyId'                => array('columnName' => 'company_properties.company_id', 		'default' => null),
                'companyPropertiesSubdomain'                => array('columnName' => 'company_properties.subdomain_url', 	'default' => null),
                'companyPropertiesIsSubnoc'                 => array('columnName' => 'company_properties.is_subnoc', 		'default' => null),
                'companyPropertiesHasChild'                 => array('columnName' => 'company_properties.has_child', 		'default' => null),
                'companyPropertiesAlertEmail'               => array('columnName' => 'company_properties.alert_email', 		'default' => null),
                'companyPropertiesBannerName'               => array('columnName' => 'company_properties.banner_name', 		'default' => null),
                'companyPropertiesBannerExtension'          => array('columnName' => 'company_properties.banner_extension', 		'default' => null),
                'companyPropertiesBanner'                   => array('columnName' => 'company_properties.banner', 		'default' => null),
                'companyPropertiesAffiliation'              => array('columnName' => 'company_properties.affiliation', 		'default' => null),
                'companyPropertiesAffiliationLogo'          => array('columnName' => 'company_properties.affiliation_logo', 'default' => null),
                'companyPropertiesAffiliationLogoName'      => array('columnName' => 'company_properties.affiliation_logo_name', 'default' => null),
                'companyPropertiesAffiliationLogoExtension' => array('columnName' => 'company_properties.affiliation_logo_extension', 'default' => null),
                'companyPropertiesAffiliationOrgName'       => array('columnName' => 'company_properties.affiliation_org_name', 'default' => null),
                'companyPropertiesAvailableTo'              => array('columnName' => 'company_properties.available_to', 'default' => null),
                'companyPropertiesBenefits'                 => array('columnName' => 'company_properties.benefits', 'default' => null),
                'companyPropertiesAffiliationCategoryId'    => array('columnName' => 'company_properties.affiliation_category_id', 'default' => null),
                'companyPropertiesAffiliationCode'          => array('columnName' => 'company_properties.affiliation_code', 'default' => null),
                'companyPropertiesSubDomainTemplate'        => array('columnName' => 'company_properties.subdomain_template', 'default' => null),
                'companyPropertiesConnectionApprovalMode'   => array('columnName' => 'company_properties.connection_approval_mode', 'default' => null),
                'companyPropertiesIsCustomTermEnable'       => array('columnName' => 'company_properties.is_custom_term_enable', 'default' => null),
                'companyPropertiesCustomTermData'           => array('columnName' => 'company_properties.custom_term_data', 'default' => null),
                'companyPropertiesCoConnectionEmail'        => array('columnName' => 'company_properties.auto_approval_connection_email', 'default' => null),
                //Suman: CPNOS-12328
                'companyPropertiesIsCustomEvatarEnabled'    => array('columnName' => 'company_properties.is_custom_evatar_enabled', 'default' => null),
                'companyPropertiesIsAllowedFreeDriverSignup'=> array('columnName' => 'company_properties.is_allowed_free_signup', 'default' => null), //Suman: CPNOS-11992
                'companyPropertiesJustChargeFlag'           => array('columnName' => 'company_properties.just_charge_flag', 'default' => null), //Suman: Just charge
                'companyPropertiesHceCardEnabled'           => array('columnName' => 'company_properties.hce_card_enabled', 'default' => null), //Suman: Just charge
                // VN: CPSRV-1455 - As a PM role I want to be able to enable WEX support for an Org so that they can pay for charging with a WEX card
                'companyPropertiesIsWexEnabled'             => array('columnName' => 'company_properties.is_wex_enabled', 'default' => null),
                'companyPropertiesWexEnableDate'            => array('columnName' => 'company_properties.wex_enabled_date', 'default' => null),
                'companyPropertiesWexParentAccoountName'    => array('columnName' => 'company_properties.wex_parent_account_name', 'default' => null),
                'companyPropertiesIsGsaEnabled'             => array('columnName' => 'company_properties.is_gsa_enabled', 'default' => null),
                // GT: CPSRV-5920 : As a utility customer I want ChargePoint to send one branded card to any driver that 'converts' to my branded experience.
                'cp_cpc_part_number'                        => array('columnName' => 'company_properties.cpc_part_number', 'default' => null),
                'cp_send_free_cards_on_branding'            => array('columnName' => 'company_properties.send_free_cards_on_branding', 'default' => null),
                'cp_free_card_count'                        => array('columnName' => 'company_properties.free_card_count', 'default' => null),
                // dbansal:CPSRV-6011
                'cp_cost_per_card'                          => array('columnName' => 'company_properties.cost_per_card', 'default' => null),
                'cards_per_mailer'                          => array('columnName' => 'company_properties.cards_per_mailer', 'default' => null),
                // GT: CPSRV-7949 - As a PM I want to be able to enable Scheduled Charging for an Org so that I can run a beta program.
                'cp_sch_charging_enabled'                   => array('columnName' => 'company_properties.schedule_charge_enabled', 'default' => NULL),
                // Ankit SDGE_DEMO
                'cp_utility_program_enabled'                => array('columnName' => 'company_properties.utility_program_enabled', 'default' => 0),
                // Suman: CPSRV-12110 - As an admin I should be able to add UUID of user in connection approval form of PG&E, SCE and SDG&E company.
                'cp_is_utility_company'                     => array('columnName' => 'company_properties.is_utility_company ', 'default' => 0)
            );
        }
    }