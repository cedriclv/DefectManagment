# DefectManagmentTool

Application to record, categorize and follow up inventory defects detected in inventory weekly check done in a logistic wharehouse.

This tool can be used by several roles: quality analyst / quality manager / quality network manager (in charge of several warehouses)

The main tasks for the different roles are as follow:
- quality analyst => while doing the quality investigation, the analyst will fill the details about the defect, using standards pre defined defect reasons
- quality manager => will manily use the dashboard of the application to look at defect trends and defect distribution, allowing to focus on the best opportunity to improve quality (reduce defects)
- quality network manager => will mainly compare the performance accross the warehouses, seeking for outliners and best practices or specific support


The main defects are the following:
- inbound flow : wrong quantity stow in inventory bin / item stowed in the adjacent bin
- outbound flow : wrong quantity picked / quantity picked in the wrong inventory bin location
- others : theft - unknown reason


The main user stories are below:

- As a USER, I need to be able to create an account and to be loggued in the application
- As a ANALYST, I need to be able to register and fill the main details (except attachement upload)
- As a ANALYST, I need to be able to upload an attachement to a defect to contextualize my investigation
- As a MANAGER, I need to be able to see the global performance against the last 5 weeks
- As a MANAGER, I need to be able to see the breakdown per defect reasons for a particular week
- As an ADMIN, I need to be able to add/remove new defect reasons available for invesigations
