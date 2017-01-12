Commands
========

ehdev:initRoleAcl
-----------------
With that Symfony command you can init roles and acls on our custom oro bundle.
Acls will be overridden every time you execute the command.

### Roles
Add a `roles.yml` in `AcmeBundle/Resources/config/ehdev`

Config sample:
``` yml
ROLE_NAMESPACE_BUNDLE_ROLE1_ID: Label 1
ROLE_NAMESPACE_BUNDLE_ROLE2_ID: Label 2
```
!ATTENTION!: ATM it's not supported to delete roles.

###ACL
Add a `acl.yml` in `AcmeBundle/Resources/config/ehdev`

Config sample:
``` yml
ROLE_NAMESPACE_BUNDLE_ROLE1_ID:
    label: Label
    permissions:
        entity|Oro\Bundle\ActivityListBundle\Entity\ActivityList: [VIEW_SYSTEM, CREATE_SYSTEM, EDIT_SYSTEM, DELETE_SYSTEM]
        entity|Oro\Bundle\DashboardBundle\Entity\Dashboard: [VIEW_DEEP, CREATE_DEEP, EDIT_DEEP]
        entity|Oro\Bundle\EmailBundle\Entity\EmailUser: [VIEW_BASIC, CREATE_BASIC, EDIT_BASIC]
        entity|Oro\Bundle\OrganizationBundle\Entity\Organization: [VIEW_SYSTEM]
        entity|Oro\Bundle\TagBundle\Entity\Tag: [VIEW_SYSTEM]
        action|oro_dataaudit_history: [EXECUTE]
        action|oro_search: [EXECUTE]
```
