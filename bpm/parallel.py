import math
import multiprocessing as mp
import sys

from bpm import conf

# The total number of "steps" to generate a set of BPMs
steps = 10000000

# Costs of various things to make progress bar a little more accurate
costs = { 'load_genes': 50,
        }

# A shared global variable used for visual progress
counter = mp.Value('i', 0)

def pmap(*args, **kargs):
    '''
    This is a convenient wrapper function that will parallelize a map function
    if the capability exists. It degrades to a regular map function if not.
    '''
    if conf.processes > 1:
        return mp.Pool(processes=conf.processes).map(*args, **kargs)
    else:
        return map(*args, **kargs)

def print_progress():
    if not conf.progress:
        return

    spaces = 60

    if counter.value == steps:
        progress = spaces
        blanks = 0
        pnumber = 100
    else:
        percent = float(counter.value) / float(steps)
        progress = int(math.ceil(percent * spaces))
        blanks = spaces - progress
        pnumber = math.ceil(percent * 100)

    print '\r[%s%s] %d%%' % ('#' * progress, ' ' * blanks, pnumber),
    sys.stdout.flush()

def inc_counter(incby=1):
    counter.value += incby

def get_counter():
    return counter.value

