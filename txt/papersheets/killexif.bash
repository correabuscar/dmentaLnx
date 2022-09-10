#!/bin/bash

#set -x

onexit() {
  echo "exit code $? lastfile: $i"
  echo "processed $c files"
  if test "$ok" == "1"; then
    echo "All done OK!"
  fi
}
ok=0
trap onexit EXIT 

let 'c=0'  #returns -1
set -e

#Only handles spaces, not \t for example. Do it without using xargs
zeroed_unspaced_unzeroed="$(find . -iname '*.jpg' -a -print0 | tr ' ' '\001' | tr '\000' " ")"
#unspace="${zeroed// /\1}"
#unzero="${unspace//\0/ }"
for i in $zeroed_unspaced_unzeroed; do #no quotes!
  i="${i//$'\001'/ }"
  #Gentoo package media-gfx/exiv2 has /usr/bin/exiv2
  exiv2 -k -da rm "$i"
  #^auto exit due to 'set -e' above
  set +e
  exiv2 -u -k print "$i";ec="$?"
  ec=253
  if test "$ec" != 253; then
    echo "Found exif!"
    exit "$ec"
  fi
#  echo "$i"
  #set +e
  let 'c++'  #returns -1
  set -e
done

#function verif() {
#  exiv2 -u -k print "$@"
#}
#export -f verif
set -e
#find . -iname '*.jpg' -a -print0 | xargs --null -n 1 -P1 -I '{}' -- verif '{}'
set +e
hmf="$(find . -iname '*.jpg'|wc -l)"
if "$c" != "$hmf"; then
  echo "Verify failed: expected $hmf files but only processed $c files!"
fi
find . -iname '*.jpg' -a -print0 | xargs --exit --null -n 1 -P1 -I '{}' -- exiv2 -u -k print '{}';ec2="$?"
if test "$ec2" != "123"; then
  echo "Verify failed!"
fi
set -e


ok=1
