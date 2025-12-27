# ProtectEntireWiki
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/LesBoys43/mediawiki-extension-ProtectEntireWiki/SyntaxCheck.yaml?branch=master&style=for-the-badge&logo=githubactions&logoColor=lime&label=syntax)
![GitHub Actions Workflow Status](https://img.shields.io/github/actions/workflow/status/LesBoys43/mediawiki-extension-ProtectEntireWiki/InternalErrorCheck.yml?branch=master&style=for-the-badge&logo=githubactions&logoColor=lime&label=build)
![GitHub License](https://img.shields.io/github/license/LesBoys43/mediawiki-extension-ProtectEntireWiki?style=for-the-badge&logo=bsd&logoColor=cyan)
![GitHub Tag](https://img.shields.io/github/v/tag/LesBoys43/mediawiki-extension-ProtectEntireWiki?include_prereleases&style=for-the-badge&logo=wikimediafoundation&logoColor=lightgrey&label=version)
![GitHub Repo stars](https://img.shields.io/github/stars/LesBoys43/mediawiki-extension-ProtectEntireWiki?style=for-the-badge&logo=wikidata&logoColor=skyblue)

ProtectEntireWiki is a extension that can protect the entire wiki or the entire namespace.

## Setup
Download this extension, put it to `extensions` directory, and add a line `wfLoadExtension("ProtectEntireWiki");` to `LocalSettings.php`

## Configuration
### LocalSettings.php Variables
**$wgPEWNotifyUser**
TYPE: MediaWiki Username
DESC: This variable configs the user that to notify rollback failed
DEFAULT: `ProtectEntireWiki`
NOTICE: The user must already exist

**$wgPEWProtectionConfigFileLoc**
TYPE: Path on Server
DESC: This variable configs the location that stores the protection configuration
DEFAULT: `{EXT_DIR}/pconf.txt`
NOTICE: The file must already exist

**$wgPEWAllowEditImpliesAllowRollback**
TYPE: Boolean
DESC: This variable configs allow edit implies allow rollback or not
DEFAULT: `true`

### Protection Configuration
You can edit the file that located at value of `$wgPEWProtectionConfigFileLoc` to configuration protection rules currently

#### Syntax
##### Basics
A lline means a rule, no comments support

##### Line Syntax
**Protect a namespace, only user that has defined group can edit**
```
<NamespaceKey>=<Group1>,<Group2>,...
```

**Protect a namespace, nobody can edit**
```
<NamespaceKey>
```

**Protect the entire wiki, only user that has defined group can edit**
```
*=<Group1>,<Group2>,...
```

**Protect the entire wiki, nobody can edit**
```
*
```

**Protect a namespace, disallow rollback**
```
<NamespaceKey>=disallow-rollback,...
```
NOTICE: Allow edit implies allow rollback

**Protect the entire wiki, disallow rollback**
```
*=disallow-rollback,...
```
NOTICE: Allow edit implies allow rollback

##### BNF Reference
```bnf
<Config> := <Rule> | <Config> "\n" <Rule>
<Rule> := <Subject> "=" <Object> | <Subject>
<Subject> := "*" | <NamespaceKey>
<NamespaceKey> := (Any String)
<Object> := <Group> | <Object> "," <Group> | "disallow-rollback" | <Object> "," "disallow-rollback" | "disallow-rollback" "," <Object>
<Group> := (Any String)
```

### FAQ
1. Can I config rules on GUI
Currently you can't, see Roadmap

2. Can I audit failed tries
Currently you can't, see Roadmap

3. Is this project free (as free speech, also as free beer)
Yes, this project uses BSD-3-Clause, a OSI-approved free license

4. What is namespace key
Namespace key is the internal namespace identifier in MediaWiki, for example, namespace key of Module is nstab-module

### Roadmap
See ROADMAP.md

### Security
See SECURITY.md

### CoC
See CODE-OF-CONDUCT.md