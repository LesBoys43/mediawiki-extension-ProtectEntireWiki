# Roadmap
## Current version
1.0.rc1

## Next version
1.0.rc2

## Future features
* Audit logs
* A special pages that can edit protection config rules

# Versioning rules
* The first number always be 1
* The second number is the major version number
* We has 1~6 .lbX version for internal testing and developing
* We has 1~3 .rcX version for bug fixing, on .rcX state, no new feature allowed
* We has .rcFinal version for stablity testing, only bug fixing are accepted on this state
* We has normal released for using, no any modifications (exclude cherry picking) are allowed on this state
* When a version entered .rcFinal state, the master will be .lb1 of next version, and the current version will be moved to a new branch
* Every version has a tag
* Tag named with REL1_XX(_LBX)(_RCX)(_RCFINAL)