
Name: app-incoming-firewall
Epoch: 1
Version: 1.4.20
Release: 1%{dist}
Summary: Incoming Firewall
License: GPLv3
Group: ClearOS/Apps
Source: %{name}-%{version}.tar.gz
Buildarch: noarch
Requires: %{name}-core = 1:%{version}-%{release}
Requires: app-base
Requires: app-network

%description
The Incoming Firewall app keeps the bad guys out by limiting access to your system and blocking unwanted connections.

%package core
Summary: Incoming Firewall - Core
License: LGPLv3
Group: ClearOS/Libraries
Requires: app-base-core
Requires: app-firewall-core >= 1:1.4.15
Requires: app-network-core

%description core
The Incoming Firewall app keeps the bad guys out by limiting access to your system and blocking unwanted connections.

This package provides the core API and libraries.

%prep
%setup -q
%build

%install
mkdir -p -m 755 %{buildroot}/usr/clearos/apps/incoming_firewall
cp -r * %{buildroot}/usr/clearos/apps/incoming_firewall/


%post
logger -p local6.notice -t installer 'app-incoming-firewall - installing'

%post core
logger -p local6.notice -t installer 'app-incoming-firewall-core - installing'

if [ $1 -eq 1 ]; then
    [ -x /usr/clearos/apps/incoming_firewall/deploy/install ] && /usr/clearos/apps/incoming_firewall/deploy/install
fi

[ -x /usr/clearos/apps/incoming_firewall/deploy/upgrade ] && /usr/clearos/apps/incoming_firewall/deploy/upgrade

exit 0

%preun
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-incoming-firewall - uninstalling'
fi

%preun core
if [ $1 -eq 0 ]; then
    logger -p local6.notice -t installer 'app-incoming-firewall-core - uninstalling'
    [ -x /usr/clearos/apps/incoming_firewall/deploy/uninstall ] && /usr/clearos/apps/incoming_firewall/deploy/uninstall
fi

exit 0

%files
%defattr(-,root,root)
/usr/clearos/apps/incoming_firewall/controllers
/usr/clearos/apps/incoming_firewall/htdocs
/usr/clearos/apps/incoming_firewall/views

%files core
%defattr(-,root,root)
%exclude /usr/clearos/apps/incoming_firewall/packaging
%exclude /usr/clearos/apps/incoming_firewall/tests
%dir /usr/clearos/apps/incoming_firewall
/usr/clearos/apps/incoming_firewall/deploy
/usr/clearos/apps/incoming_firewall/language
/usr/clearos/apps/incoming_firewall/libraries
