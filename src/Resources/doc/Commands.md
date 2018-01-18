Commands
========

ehdev:initRoleAcl
-----------------
With that Symfony command you can init roles and acls on our custom oro bundle.
Acls will be overridden every time you execute the command.

### Roles && ACLs
Add a `acl_roles.yml` in `AcmeBundle/Resources/config/ehdev`

Config sample:
``` yml
ROLE_NAMESPACE_BUNDLE_ROLE1_ID:
    label: Label
    descriptions: [OPTIONAL] Extended description for role 
    permissions:
        entity|Oro\Bundle\ActivityListBundle\Entity\ActivityList: [VIEW_SYSTEM, CREATE_SYSTEM, EDIT_SYSTEM, DELETE_SYSTEM]
        entity|Oro\Bundle\DashboardBundle\Entity\Dashboard: [VIEW_DEEP, CREATE_DEEP, EDIT_DEEP]
        entity|Oro\Bundle\EmailBundle\Entity\EmailUser: [VIEW_BASIC, CREATE_BASIC, EDIT_BASIC]
        entity|Oro\Bundle\OrganizationBundle\Entity\Organization: [VIEW_SYSTEM]
        entity|Oro\Bundle\TagBundle\Entity\Tag: [VIEW_SYSTEM]
        action|oro_dataaudit_history: [EXECUTE]
        action|oro_search: [EXECUTE]
```
!ATTENTION!: ATM it's not supported to rename or delete roles.

Possible permission keys:
`[VIEW|CREATE|EDIT|DELETE|ASSIGN|SHARE]_[BASIC|LOCAL|DEEP|GLOBAL|SYSTEM]|[EXECUTE]`


ehdev:missingEntityLabels
-------------------------
Command to identify missing entity translations
### Options
You could use the following optional options
* `--ignore-oro` to ignore entities who lives in the `Oro\` Namespace e.g. `Oro\Bundle\ReportBundle\Entity\CalendarDate`
* `--ignore-extend` to ignore entities who lives in the `Extend\` Namespace e.g. `Extend\Entity\EV_Ce_Attendee_Status`
