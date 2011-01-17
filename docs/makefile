USER_INI = user.ini
PROGRAMMER_INI = programmer.ini
INTERNAL_INI = internal.ini

PHPDOC = phpdoc -c ./phpdoc/

DOXYGEN = doxygen ./doxygen/

.PHONY: doxygen phpdoc

all: doxygen phpdoc

clean:
	rm -rf ./doc/user/* ./doc/programmer/* ./doc/internal/*
	
doxygen:
	$(DOXYGEN)$(USER_INI) > /dev/null
	-cat ./doxygen/user_warn.log | grep -v \\\\file | grep -v \\\\link
	$(DOXYGEN)$(PROGRAMMER_INI) > /dev/null
	-cat ./doxygen/programmer_warn.log | grep -v \\\\file | grep -v \\\\link
	$(DOXYGEN)$(INTERNAL_INI) > /dev/null
	-cat ./doxygen/internal_warn.log | grep -v \\\\file | grep -v \\\\link

phpdoc:
	$(PHPDOC)$(USER_INI) > /dev/null
	$(PHPDOC)$(PROGRAMMER_INI) > /dev/null
	$(PHPDOC)$(INTERNAL_INI) > /dev/null
