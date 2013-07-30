xtmembers_membership
====================

Contao extension xtmembers_membership

This plugin extends the contao members by a membership fee and a membership date to manage the membership fee payments. The fee payments can be exported to a Microsoft Excel spreadsheet file.
Each member which has at least the field

**Membership since** (contains the beginning date of the membership)

filled in will be available for the xtmembers_membership extension. You should also fill in the field

**Membership fee** (currency field that contains the annual membership fee)

to define an annual membership fee for the member.

A click to the **Detailed statistics on mebmership fees over the years** icon next to each member in the members table automatically creates the missing entries from the beginning of the membership until the current date for all members. For the selected member you can individually change the annual fee, enter the payed amount and set a status of either *payed*, *postponed*, or *freed*.
Using the **Export fees** link in the members table generates a Microsoft Excel spreadsheet containing all members with a membership date and the payments over the years.
