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
    percent = float(counter.value) / float(steps)
    progress = int(math.ceil(percent * spaces))
    blanks = spaces - progress

    print '\r[%s%s] %d%%' % ('#' * progress, ' ' * blanks, 
                             math.ceil(percent * 100)),
    sys.stdout.flush()

def inc_counter(incby=1):
    counter.value += incby

def get_counter():
    return counter.value

