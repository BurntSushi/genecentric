import bpm

import argparse
import multiprocessing as mp

try:
    __cpus = mp.cpu_count()

    # be nice to Funcassociate by default
    __default_cpus = min(6, mp.cpu_count())
except NotImplementedError:
    __cpus = 1
    __default_cpus = 1

parser = argparse.ArgumentParser(
    description='GO enrichment for BPMs',
    formatter_class=argparse.ArgumentDefaultsHelpFormatter)
aa = parser.add_argument
aa('emap', type=str,
   metavar='EMAP_FILE', help='Location of the EMAP file.')
aa('bpm', type=str,
   metavar='BPM_FILE', help='Location of the BPM file.')
aa('-p', '--processes', dest='processes', type=int, default=__default_cpus,
   metavar='CPUs', help='The number of processes to run in parallel. If set to '
                        '1, the multiprocessing module will not be used. '
                        'You should also be nice to Funcassociate and not set '
                        'this too high.')
aa('--no-progress', dest='progress', action='store_false',
   help='If set, the progress bar will not be shown.')
aa('-v', '--verbose', dest='verbose', action='store_true',
   help='If set, more output will be shown.')

conf = parser.parse_args()

# Protect the user from themselves.
# If the provided number of processes is larger than the detected number of
# CPUs, forcefully lower it to the number of CPUs.
if conf.processes > __cpus:
    conf.processes = __cpus

# Set the global conf variable
bpm.conf = conf

