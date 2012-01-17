import multiprocessing as mp

from bpm import conf

# This is a convenient wrapper function that will parallelize a map function
# if the capability exists. It degrades to a regular map function if not.
def pmap(*args, **kargs):
    if conf.processes > 1:
        return mp.Pool(processes=conf.processes).map(*args, **kargs)
    else:
        return map(*args, **kargs)

