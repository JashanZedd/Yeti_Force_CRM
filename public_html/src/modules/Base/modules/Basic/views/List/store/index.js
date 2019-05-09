/* {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} */

export default class List extends VuexClass {
  constructor() {
    super()
    this.state = {
      listTest: 'test variable',
      moduleName: 'List'
    }
    this.namespaced = false
  }

  fetchData() {
    //TODO request based on module name
    return new Promise((resolve, reject) => {
      const data = {
        columns: [
          {
            //q options
            required: true,
            align: 'left',
            field: row => row.name,
            format: val => `${val}`,
            sortable: true,
            //already existing properties suited q
            name: 'desc',
            label: 'Account Name',
            //yf options
            picklistValues: null,
            id: 1,
            tabid: 6,
            table: 'vtiger_account',
            column: 'accountname',
            columntype: false,
            helpinfo: '',
            summaryfield: 1,
            header_field: null,
            maxlengthtext: 0,
            maxwidthcolumn: 0,
            masseditable: 1,
            uitype: 2,
            typeofdata: 'V~M',
            displaytype: 1,
            generatedtype: 1,
            readonly: 0,
            visible: 0,
            presence: 0,
            defaultvalue: '',
            maximumlength: '100',
            sequence: 1,
            quickcreate: 0,
            quicksequence: 1,
            info_type: 'BAS',
            block: {
              id: 9,
              label: 'LBL_ACCOUNT_INFORMATION',
              sequence: 1,
              showtitle: 0,
              visible: 0,
              increateview: 0,
              ineditview: 0,
              indetailview: 0,
              display_status: 2,
              iscustom: 0,
              module: {
                id: 6,
                name: 'Accounts',
                label: 'Accounts',
                version: null,
                minversion: false,
                maxversion: false,
                presence: 0,
                ownedby: 0,
                tabsequence: 5,
                parent: 'Sales',
                customized: 0,
                isentitytype: 1,
                entityidcolumn: false,
                entityidfield: false,
                basetable: 'vtiger_account',
                basetableid: 'accountid',
                customtable: false,
                grouptable: false,
                type: 0,
                tableName: null
              }
            },
            fieldparams: '',
            fieldDataType: 'string'
          },
          {
            align: 'left',
            field: 'website',
            sortable: true,
            name: 'website',
            label: 'Website',
            picklistValues: null,
            id: 4,
            tabid: 6,
            table: 'vtiger_account',
            column: 'website',
            columntype: false,
            helpinfo: '',
            summaryfield: 1,
            header_field: null,
            maxlengthtext: 0,
            maxwidthcolumn: 0,
            masseditable: 1,
            uitype: 17,
            typeofdata: 'V~O',
            displaytype: 1,
            generatedtype: 1,
            readonly: 0,
            visible: 0,
            presence: 2,
            defaultvalue: '',
            maximumlength: '255',
            sequence: 5,
            quickcreate: 2,
            quicksequence: 4,
            info_type: 'BAS',
            block: {
              id: 195,
              label: 'LBL_CONTACT_INFO',
              sequence: 2,
              showtitle: 0,
              visible: 0,
              increateview: 0,
              ineditview: 0,
              indetailview: 0,
              display_status: 2,
              iscustom: 0,
              module: {
                id: 6,
                name: 'Accounts',
                label: 'Accounts',
                version: null,
                minversion: false,
                maxversion: false,
                presence: 0,
                ownedby: 0,
                tabsequence: 5,
                parent: 'Sales',
                customized: 0,
                isentitytype: 1,
                entityidcolumn: false,
                entityidfield: false,
                basetable: 'vtiger_account',
                basetableid: 'accountid',
                customtable: false,
                grouptable: false,
                type: 0,
                tableName: null
              }
            },
            fieldparams: '',
            fieldDataType: 'url'
          },
          {
            align: 'left',
            field: 'phone',
            sortable: true,
            picklistValues: null,
            id: 3,
            name: 'phone',
            tabid: 6,
            label: 'Phone',
            table: 'vtiger_account',
            column: 'phone',
            columntype: false,
            helpinfo: '',
            summaryfield: 1,
            header_field: null,
            maxlengthtext: 0,
            maxwidthcolumn: 0,
            masseditable: 1,
            uitype: 11,
            typeofdata: 'V~O',
            displaytype: 1,
            generatedtype: 1,
            readonly: 0,
            visible: 0,
            presence: 2,
            defaultvalue: '',
            maximumlength: '30',
            sequence: 2,
            quickcreate: 2,
            quicksequence: 5,
            info_type: 'BAS',
            block: {
              id: 195,
              label: 'LBL_CONTACT_INFO',
              sequence: 2,
              showtitle: 0,
              visible: 0,
              increateview: 0,
              ineditview: 0,
              indetailview: 0,
              display_status: 2,
              iscustom: 0,
              module: {
                id: 6,
                name: 'Accounts',
                label: 'Accounts',
                version: null,
                minversion: false,
                maxversion: false,
                presence: 0,
                ownedby: 0,
                tabsequence: 5,
                parent: 'Sales',
                customized: 0,
                isentitytype: 1,
                entityidcolumn: false,
                entityidfield: false,
                basetable: 'vtiger_account',
                basetableid: 'accountid',
                customtable: false,
                grouptable: false,
                type: 0,
                tableName: null
              }
            },
            fieldparams: '',
            fieldDataType: 'phone'
          },
          {
            align: 'left',
            field: 'assigned_user_id',
            sortable: true,
            picklistValues: null,
            id: 20,
            name: 'assigned_user_id',
            tabid: 6,
            label: 'Assigned To',
            table: 'vtiger_crmentity',
            column: 'smownerid',
            columntype: false,
            helpinfo: '',
            summaryfield: 1,
            header_field: null,
            maxlengthtext: 0,
            maxwidthcolumn: 0,
            masseditable: 1,
            uitype: 53,
            typeofdata: 'V~M',
            displaytype: 1,
            generatedtype: 1,
            readonly: 0,
            visible: 0,
            presence: 0,
            defaultvalue: '',
            maximumlength: '65535',
            sequence: 6,
            quickcreate: 0,
            quicksequence: 2,
            info_type: 'BAS',
            block: {
              id: 9,
              label: 'LBL_ACCOUNT_INFORMATION',
              sequence: 1,
              showtitle: 0,
              visible: 0,
              increateview: 0,
              ineditview: 0,
              indetailview: 0,
              display_status: 2,
              iscustom: 0,
              module: {
                id: 6,
                name: 'Accounts',
                label: 'Accounts',
                version: null,
                minversion: false,
                maxversion: false,
                presence: 0,
                ownedby: 0,
                tabsequence: 5,
                parent: 'Sales',
                customized: 0,
                isentitytype: 1,
                entityidcolumn: false,
                entityidfield: false,
                basetable: 'vtiger_account',
                basetableid: 'accountid',
                customtable: false,
                grouptable: false,
                type: 0,
                tableName: null
              }
            },
            fieldparams: '',
            fieldDataType: 'owner'
          }
        ],
        data: [
          {
            id: 1,
            name: 'YetiForce Sp. z o.o.',
            website: '',
            phone: '',
            assigned_user_id: 'YetiForce Demo'
          },
          {
            id: 2,
            name: 'Ambrozja. Sklep cukierniczy. Pawluczuk J...',
            website: 'http://www.biznespolska.pl/?p=0#baza_fir...',
            phone: '+48 698 988 677',
            assigned_user_id: ' YetiForce Guest'
          },
          {
            id: 3,
            name: 'Amtra. Sp. z o.o.',
            website: 'http://www.bazafirm24.pl/',
            phone: '+48 796 384 765',
            assigned_user_id: 'Administrator'
          },
          {
            id: 4,
            name: 'YetiForce Sp. z o.o.2',
            website: '',
            phone: '',
            assigned_user_id: 'YetiForce Demo'
          },
          {
            id: 5,
            name: 'Ambrozja. Sklep cukierniczy. Pawluczuk J...2',
            website: 'http://www.biznespolska.pl/?p=0#baza_fir...',
            phone: '+48 698 988 677',
            assigned_user_id: ' YetiForce Guest'
          },
          {
            id: 6,
            name: 'Amtra. Sp. z o.o.2',
            website: 'http://www.bazafirm24.pl/',
            phone: '+48 796 384 765',
            assigned_user_id: 'Administrator'
          },
          {
            id: 7,
            name: 'YetiForce Sp. z o.o.3',
            website: '',
            phone: '',
            assigned_user_id: 'YetiForce Demo'
          },
          {
            id: 8,
            name: 'Ambrozja. Sklep cukierniczy. Pawluczuk J...3',
            website: 'http://www.biznespolska.pl/?p=0#baza_fir...',
            phone: '+48 698 988 677',
            assigned_user_id: ' YetiForce Guest'
          },
          {
            id: 9,
            name: 'Amtra. Sp. z o.o.3',
            website: 'http://www.bazafirm34.pl/',
            phone: '+48 796 384 765',
            assigned_user_id: 'Administrator'
          }
        ]
      }
      resolve(data)
    })
  }
  set updateTestVariable(value) {
    this.state.listTest = value
  }
  get getTestVariable() {
    return this.state.listTest
  }
  get getModuleName() {
    return this.state.moduleName
  }
  getData() {
    return 'test'
  }
}
