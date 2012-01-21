import bpm

import argparse

parser = argparse.ArgumentParser(
    description='GO enrichment for BPMs',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('emap', type=str,
   metavar='EMAP_FILE', help='Location of the EMAP file.')
aa('bpm', type=str,
   metavar='BPM_FILE', help='Location of the BPM file.')
aa('-v', '--verbose', dest='verbose', action='store_true',
   help='If set, more output will be shown.')

conf = parser.parse_args()

# Set the global conf variable
bpm.conf = conf

