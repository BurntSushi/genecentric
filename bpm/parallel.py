import multiprocessing as mp

from bpm import conf
from bpm import debug

# This is a convenient wrapper function that will parallelize a map function
# if the capability exists. It degrades to a regular map function if not.
def pmap(*args, **kargs):
    if conf.processes > 1:
        debug.echotime('running %d processes' % conf.processes)
        return mp.Pool(processes=conf.processes).map(*args, **kargs)
    else:
        debug.echotime('running one process')
        return map(*args, **kargs)

